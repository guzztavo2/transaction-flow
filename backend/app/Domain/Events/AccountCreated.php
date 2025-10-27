<?php
namespace App\Domain\Events;

use App\Domain\Entities\Account;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Account $account) {}

}