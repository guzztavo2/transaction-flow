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

    private User $user;

    public function __construct()
    {
        $this->user = Auth('api')->user();
    }
}
