<?php

namespace App\Domain\Events;

use App\Domain\Entities\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateUser
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly User $account) {}
}
