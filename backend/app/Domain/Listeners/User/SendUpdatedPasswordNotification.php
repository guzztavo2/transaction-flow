<?php

namespace App\Domain\Listeners\User;

use App\Domain\Events\UserPasswordUpdated;
use App\Jobs\NotificationUpdatedPasswordJob;

class SendUpdatedPasswordNotification
{

    public function handle(UserPasswordUpdated $event): void
    {
        $user = $event->user;
        NotificationUpdatedPasswordJob::dispatch($user->getId());
    }
}
