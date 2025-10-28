<?php

namespace App\Domain\Listeners\Account;

use App\Domain\Events\AccountCreated;
use App\Jobs\NotificationWelcomeAccountJob;

class SendWelcomeNotification
{

    public function handle(AccountCreated $event): void
    {
        $account = $event->account;
        NotificationWelcomeAccountJob::dispatch($account->getId());
    }
}
