<?php

namespace App\Domain\Listeners\Account;

use App\Domain\Events\AccountCreated;
use Illuminate\Support\Facades\Log;

class CreatedLog
{

    public function handle(AccountCreated $event): void
    {
        Log::info('Account created with ID: ' . $event->account->getId());
    }
}
