<?php

namespace App\Http\Services;

use App\Models\Account;
use App\Models\User;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthService extends Service
{
    private const TOKEN_MAX_SECONDS = 7200;  // 2 HOURS
    private const RECOVERY_PASSWORD_TOKEN_HOUR = 2;  // 2 HOURS

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

        $user = User::new($request['name'], $request['email'], $request['password']);
        $account = Account::new($user, $request['bank'], $request['agency'], $request['number_account'], 0.0);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'bank' => $account->bank,
            'agency' => $account->agency,
            'number_account' => $account->number_account,
            'balance' => $account->balance,
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['email:strict,dns,spoof', 'required', 'max:100', 'string'],
            'password' => ['required', 'max:100', 'string']
        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->setTTL(self::TOKEN_MAX_SECONDS)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = auth('api')->user();
        $user->notify(new ResetPassword());
        return response()->json($user->toArray());
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function changePassword(Request $request, string $token = null)
    {
        $request->validate([
            'password' => ['required', 'max:100', 'string', 'same:password'],
            'confirm_password' => ['required', 'max:100', 'string', 'same:confirm_password'],
            'new_password' => ['required', 'max:100', 'string', Password::min(8)->mixedCase(), 'same:confirm_new_password'],
            'confirm_new_password' => ['required', 'max:100', 'string', 'same:new_password']
        ]);

        if (empty($token) and empty(auth('api')->user()))
            return response()->json(['error' => 'Unauthorized access'], 401);

        return $this->changePasswordWithToken($request, $token);

        $user = auth('api')->user();

        if ($isError = $user->updatePassword($request->new_password, $request->password) != true)
            return response()->json($isError, 401);

        $credentials = ['email' => $user->email, 'password' => $request->new_password];

        if (!$token = auth('api')->setTTL(self::TOKEN_MAX_SECONDS)->attempt($credentials))
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

        $user->notify(new ResetPassword(self::RECOVERY_PASSWORD_TOKEN_HOUR));
        return response()->json('An email was sent to reset your password.', 200);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60  // convert to seconds to hour
        ]);
    }
}
