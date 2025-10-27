<?php

namespace App\Jobs;

use App\Notifications\WelcomeAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Account as AccountModel;
use App\Domain\Entities\Account;

class MailWelcomeAccountJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $accountId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $account = AccountModel::find($this->accountId);
        if (!$account) return;

        $account->user->notify(new WelcomeAccount(Account::fromArray($account->toArray())));
    }
}
