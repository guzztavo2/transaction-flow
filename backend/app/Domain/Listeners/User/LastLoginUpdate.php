<?php

namespace App\Domain\Listeners\User;

use App\Domain\Events\UserLogin;
use App\Domain\Repositories\User\UserRepositoryInterface;

class LastLoginUpdate
{

    public function handle(UserLogin $event, UserRepositoryInterface $userRepositoryInterface): void
    {
        $user = $event->user;
        $userRepositoryInterface->updateLastLogin($user);
    }
}
