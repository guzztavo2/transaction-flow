<?php

namespace Tests\Fake;

use App\Domain\Repositories\Account\AccountRepositoryInterface;
use App\Domain\Entities\Account;
use App\Exceptions\UnauthorizedException;
use Illuminate\Support\Str;

class FakeAccountRepository implements AccountRepositoryInterface
{
    public array $accounts = [];

    public function findById(string $id): ?Account
    {
        $account = array_filter($this->accounts, fn(Account $account) => $account->getId() === $id);
        if (!$account)
            throw new UnauthorizedException('Account id not found.');
        $account = $account[0];
        return $account ? $account : null;
    }

    public function save(Account $account): ?Account
    {
        $data = $account->toArray();
        $dataModel = null;

        if ($data['id']) {
            $account = array_filter($this->accounts, fn(Account $account) => $account->getId() === $data['id']);
            if (!$account)
                throw new UnauthorizedException('Account id not found.');

            $model = $account[0];
            $index = array_search($model, $this->accounts);
            if (!array_key_exists($index, $this->accounts))
                throw new UnauthorizedException('User id not found.');

            $dataModel = Account::FromArray($data);
            $this->accounts[$index] = $dataModel;
        } else {
            $data['id'] = Str::uuid()->toString();
            $dataModel = Account::FromArray($data);
            $this->accounts[] = $dataModel;
        }

        return $dataModel;
    }

    public function checkIfAlreadyExists(Account $account): bool
    {
        $el = array_filter($this->accounts, fn(Account $account_) =>
        $account_->getBank() === $account->getBank() && $account_->getAgency() === $account->getAgency() &&
            $account_->getNumberAccount() && $account->getNumberAccount());
        if (!$el)
            return false;

        return true;
    }
}
