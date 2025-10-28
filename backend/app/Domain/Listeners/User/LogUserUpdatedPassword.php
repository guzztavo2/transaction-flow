<?php

namespace App\Domain\Listeners\User;

use App\Domain\Events\UserPasswordUpdated;
use Illuminate\Support\Facades\Log;

class LogUserUpdatedPassword
{

    public function handle(UserPasswordUpdated $event): void
    {
        $user = $event->user;
        Log::info('User password updated for user ID: ' . $user->getId());
    }
}
