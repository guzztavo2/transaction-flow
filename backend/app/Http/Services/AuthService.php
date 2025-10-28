<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Domain\DTOs\UserData;
use App\Domain\DTOs\AccountData;
use App\Domain\Actions\User\CreateAction as CreateUser;
use App\Domain\Actions\User\LoginAction as LoginUser;
use App\Domain\Actions\User\LogoutAction as LogoutUser;
use App\Domain\Actions\User\RefreshAction as RefreshUser;
use App\Domain\Actions\User\UpdatePasswordAction as UpdateUserPasswordAction;
use App\Domain\Actions\User\UpdatePasswordWithTokenAction as UpdateUserPasswordWithTokenAction;
use App\Domain\Actions\User\SendPasswordRequestAction;
use App\Domain\Actions\Account\CreateAction as CreateAccountAction;

class AuthService extends Service
{
    private int $TOKEN_MAX_SECONDS = 7200;  // 7200 Sec = 2 HOURS
    private const RECOVERY_PASSWORD_TOKEN_HOUR = 2;

    public function __construct(
        private CreateUser $createUserAction,
        private CreateAccountAction $createAccountAction,
        private LoginUser $loginUser,
        private LogoutUser $logoutUser,
        private RefreshUser $refreshUser,
        private UpdateUserPasswordWithTokenAction $updatePasswordWithTokenAction,
        private UpdateUserPasswordAction $updatePasswordAction,
        private SendPasswordRequestAction $sendPasswordRequestAction,
    ) {}
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

        $user = ($this->createUserAction)(new UserData(null, $request['name'], $request['email'], $request['password'], null, null));
        $account = ($this->createAccountAction)(new AccountData(null, $request['bank'], $request['agency'], $request['number_account'], 0, true, $user->getId()));

        return response()->json(['name' => $user->getName(), 'email' => $user->getEmail(), 'bank' => $account->getBank(), 'agency' => $account->getAgency(), 'number_account' => $account->getNumberAccount(), 'balance' => $account->getBalance()->format()], 200);
    }

    public function login(Request $request)
    {
        $request->validate(['email' => ['email:strict,dns,spoof', 'required', 'max:100', 'string'], 'password' => ['required', 'max:100', 'string'], 'remember' => ['nullable', 'boolean']]);

        $this->TOKEN_MAX_SECONDS = $request->boolean('remember') ? 60 * 24 * 7 : 60 * 4; //time in minutes
        if (!$token = ($this->loginUser)($request->email, $request->password, $this->TOKEN_MAX_SECONDS))
            return response()->json(['error' => 'Unauthorized'], 401);
        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = auth('api')->user();
        return response()->json((array_filter($user->toArray(), fn($key, $val) => $key != 'id' && !empty($val) && boolval($val) != false, ARRAY_FILTER_USE_BOTH)), 200);
    }

    public function logout()
    {
        ($this->logoutUser)();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(Request $request)
    {
        $token_ = $request->bearerToken() ?? $request->header('authorization');

        if (!$newToken = ($this->refreshUser)($token_, $this->TOKEN_MAX_SECONDS)) {
            ($this->logoutUser)();
            return response()->json(['error' => 'Session expired or invalid. Please log in again.'], 401);
        }

        return $this->respondWithToken($newToken);
    }

    public function changePassword(Request $request, ?string $token = null)
    {
        $request->validate([
            'password' => ['required', 'max:100', 'string', 'same:confirm_password'],
            'confirm_password' => ['required', 'max:100', 'string', 'same:password'],
            'new_password' => ['required', 'max:100', 'string', Password::min(8)->mixedCase(), 'same:confirm_new_password'],
            'confirm_new_password' => ['required', 'max:100', 'string', 'same:new_password']
        ]);

        if (!empty($token))
            if (($this->updatePasswordWithTokenAction)->execute($token, $request->password, $request->new_password))
                return response()->json('Password updated successfully.', 200);
        
        $this->updatePasswordAction->execute(null, $request->new_password, $request->password);

        return $this->refresh($request);
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'max:100', 'string', 'exists:users,email']
        ]);
        ($this->sendPasswordRequestAction)(urldecode($request->email), self::RECOVERY_PASSWORD_TOKEN_HOUR);
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
