<?php

namespace App\Domain\Actions\Account;

use App\Domain\DTOs\AccountData;
use App\Domain\Entities\Account;
use App\Domain\Repositories\Account\AccountRepositoryInterface;
use App\Exceptions\AccountAlreadyExists;

class CreateAccountAction
{
    public function __construct(private AccountRepositoryInterface $repo) {}

    public function __invoke(AccountData $account)
    {

        $account = Account::fromArray([
            'bank' => $account->getBank(),
            'agency' => $account->getAgency(),
            'number_account' => $account->getNumberAccount(),
            'balance' => $account->getBalance(),
            'is_default' => $account->getIsDefault(),
            'user_id' => $account->getUserId()
        ]);
        
        if ($this->repo->checkIfAlreadyExists($account)) {
            throw new AccountAlreadyExists('Account already exists!');
        }
        return $this->repo->save($account);
    }
}
