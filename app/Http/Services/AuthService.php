<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Account;
class AuthService extends Service
{
    
    public function register(Request $request)
    {
        $request->validate([
            "name" => ['required', 'max:100', 'string'],
            "email" => ['email:strict,dns,spoof','required', 'max:100', 'string'],
            "confirm_password" => ['required', 'max:100', 'string', 'same:password'],
            "password" => ['required', 'max:100', 'string', 'same:confirm_password'],
            "bank" => ['required', 'max:100', 'string'],
            "agency" => ['required', 'max:100', 'string'],
            "number_account" => ['required', 'max:100', 'string']
        ]);

        $user = User::new($request['name'], $request['email'], $request['password']);
        $account = Account::new($user, $request['bank'], $request['agency'], $request['number_account'], 0.0);
        
        return response()->json([
            "name" => $user->name,
            "email" => $user->email,
            "bank" => $account->bank,
            "agency" => $account->agency,
            "number_account" => $account->number_account,
            "balance" => $account->balance,
        ],200);
    }

    public function login(Request $request)
    {
        $request->validate([
            "name" => ['required', 'max:100', 'string'],
            "email" => ['email:strict,dns,spoof','required', 'max:100', 'string'],
            "confirm_password" => ['required', 'max:100', 'string'],
            "password" => ['required', 'max:100', 'string']
        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth()->setTTL(7200)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }



    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            "name" => ['required', 'max:100', 'string'],
            "email" => ['email:strict,dns,spoof','required', 'max:100', 'string'],
            "confirm_password" => ['required', 'max:100', 'string'],
            "password" => ['required', 'max:100', 'string']
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            "name" => ['required', 'max:100', 'string'],
            "email" => ['email:strict,dns,spoof','required', 'max:100', 'string']
        ]);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
