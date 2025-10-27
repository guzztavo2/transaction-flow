<?php

namespace App\Domain\Listeners\Account;

use App\Domain\Events\AccountCreated;
use App\Jobs\MailWelcomeAccountJob;
class SendWelcomeEmail
{

    public function handle(AccountCreated $event): void
    {
        $account = $event->account;
        MailWelcomeAccountJob::dispatch($account->getId());
    }
}
