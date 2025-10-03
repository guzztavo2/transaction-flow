<?php

namespace App\Http\Services;

use App\Entities\AccountEntity;
use App\Entities\UserEntity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Jobs\ResetPasswordJob;
use Illuminate\Support\Facades\Redis;

class AuthService extends Service
{
    private int $TOKEN_MAX_SECONDS = 7200;  // 7200 Sec = 2 HOURS
    private const RECOVERY_PASSWORD_TOKEN_HOUR = 2;

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:100', 'string'],
            'email' => ['email:strict,dns,spoof', 'required', 'max:100', 'string', 'unique:users,email'],
            'confirm_password' => ['required', 'max:100', 'string', 'same:password'],
            'password' => ['required', 'max:100', 'string', 'same:confirm_password', Password::min(8)->mixedCase()],
            'bank' => ['required', 'max:100', 'string'],
            'agency' => ['required', 'max:100', 'string'],
            'number_account' => ['required', 'max:100', 'string']
        ]);

        $user = UserEntity::create($request['name'], $request['email'], $request['password']);
        $account = AccountEntity::create($request['bank'], $request['agency'], $request['number_account'], 0.0, true, $user->getUser());

        return response()->json([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'bank' => $account->getBank(),
            'agency' => $account->getAgency(),
            'number_account' => $account->getNumberAccount(),
            'balance' => $account->getBalance()
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate(['email' => ['email:strict,dns,spoof', 'required', 'max:100', 'string'], 'password' => ['required', 'max:100', 'string'], 'remember' => ['nullable', 'boolean']]);
        
        $credentials = $request->only(['email', 'password']);
        $user = User::where('email', $request->email)->first();

        $expiresAt = $request->boolean('remember') ? 60 * 24 * 7 : 60 * 4; //time in minutes
        $this->TOKEN_MAX_SECONDS = $expiresAt * 60; // converted to seconds

        if (!$token = auth('api')->setTTL($this->TOKEN_MAX_SECONDS)->attempt($credentials)) 
            return response()->json(['error' => 'Unauthorized'], 401);
        
        Redis::setex("user:{$user->id}:session", $this->TOKEN_MAX_SECONDS, $token);
        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = auth('api')->user();
        return response()->json((array_filter($user->toArray(), fn($key, $val) => $key != 'id' && !empty($val) && boolval($val) != false, ARRAY_FILTER_USE_BOTH)), 200);
    }

    public function logout()
    {
        $user = auth('api')->user();

        if ($user) {
            Redis::del("user:{$user->id}:session");
            auth('api')->logout();
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(Request $request)
    {
        $user = auth('api')->user();
        if(!$user){
            $this->logout();
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token_ = $request->bearerToken();

        $key = "user:{$user->id}:session";
        $validToken = Redis::get($key);

        if(!$validToken || $token_ !== $validToken){
            $this->logout();
            return response()->json(['error' => 'Session expired or invalid. Please log in again.'], 401);
        }
        
        $newToken = auth('api')->setTTL($this->TOKEN_MAX_SECONDS)->refresh();
        Redis::setex("user:{$user->id}:session", $ttl, $newToken);

        return $this->respondWithToken($newToken);
    }

    public function changePassword(Request $request, string $token = null)
    {
        $request->validate([
            'password' => ['required', 'max:100', 'string', 'same:password'],
            'confirm_password' => ['required', 'max:100', 'string', 'same:confirm_password'],
            'new_password' => ['required', 'max:100', 'string', Password::min(8)->mixedCase(), 'same:confirm_new_password'],
            'confirm_new_password' => ['required', 'max:100', 'string', 'same:new_password']
        ]);

        if (empty($token) && empty(auth('api')->user()))
            return response()->json(['error' => 'Unauthorized access'], 401);
        elseif (!empty($token))
            return $this->changePasswordWithToken($request, $token);

        $user = auth('api')->user();

        if ($isError = $user->updatePassword($request->new_password, $request->password) != true)
            return response()->json($isError, 401);

        $credentials = ['email' => $user->email, 'password' => $request->new_password];

        if (!$token = auth('api')->setTTL($this->TOKEN_MAX_SECONDS)->attempt($credentials))
            return response()->json(['error' => 'Unable to access the user, please try again.'], 401);

        return $this->respondWithToken($token);
    }

    private function changePasswordWithToken(Request $request, string $token)
    {
        $request->validate(['email' => ['required', 'max:100', 'string']]);

        $user = User::where('email', urldecode($request->email))->first();
        if (!$user)
            return response()->json(['error' => 'Unauthorized access'], 401);

        $hasToken = false;
        $user->notifications()->get()->map(function ($notification) use ($token, &$hasToken) {
            if ($notification->data['token'] === $token) {
                $notification->delete();
                $hasToken = $notification;
            }
        });

        if (empty($hasToken))
            return response()->json(['error' => 'Unauthorized access'], 401);

        if (Carbon::create($hasToken->created_at)->diffInHours(Carbon::now()) > self::RECOVERY_PASSWORD_TOKEN_HOUR)
            return response()->json(['error' => 'The token has expired.'], 401);

        if ($isError = $user->updatePassword($request->new_password, $request->password) != true)
            return response()->json($isError, 401);

        return response()->json('Password changed successfully.', 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'max:100', 'string', 'exists:users,email']
        ]);
        $user = User::where('email', $request->email)->first();
        
        ResetPasswordJob::dispatch($user->id, self::RECOVERY_PASSWORD_TOKEN_HOUR);
        return response()->json('An email was sent to reset your password.', 200);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()
            ]);
    }
}
