<?php

namespace App\Domain\Repositories\Account;

use App\Domain\Entities\Account;

interface AccountRepositoryInterface
{

    public function findById(string $id): ?Account;
    public function save(Account $account): ?Account;
    public function checkIfAlreadyExists(Account $data): bool;
}
