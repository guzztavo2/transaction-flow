<?php

namespace App\Domain\Listeners\User;

use App\Domain\Events\UserLogin;
use Illuminate\Support\Facades\Log;

class LogLogin
{

    public function handle(UserLogin $event): void
    {
        $user = $event->user;
        Log::info('User login: ' . $user->getId());
    }
}
