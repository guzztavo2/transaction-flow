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

class TransactionService extends Service
{
    public function store(Request $request)
    {
        
    }

}
