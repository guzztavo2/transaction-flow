<?php

namespace App\Domain\Entities;

use InvalidArgumentException;
use App\Domain\DTOs\AccountData;
use App\Domain\ValueObjects\Money;

final class Account
{

    public function __construct(private AccountData $data) {}

    public function credit(string|Money $amount)
    {
        if (! $amount instanceof Money)
            $amount = new Money($amount);

        $this->data->setBalance($this->getBalance()->add($amount));
    }

    public function debit(string|Money $amount)
    {
        if (! $amount instanceof Money)
            $amount = new Money($amount);

        $this->data->setBalance($this->getBalance()->subtract($amount));
    }

    public function toArray(): array
    {

        return [
            'id' => $this->data->getId(),
            'bank' => $this->data->getBank(),
            'agency' => $this->data->getAgency(),
            'number_account' => $this->data->getNumberAccount(),
            'balance' => $this->data->getBalance(),
            'is_default' => $this->data->getIsDefault(),
            'user_id' => $this->data->getUserId(),
        ];
    }

    public function getId()
    {
        return $this->data->getId();
    }
    public function getBank()
    {
        return $this->data->getBank();
    }
    public function getAgency()
    {
        return $this->data->getAgency();
    }
    public function getNumberAccount()
    {
        return $this->data->getNumberAccount();
    }
    public function getBalance()
    {
        return $this->data->getBalance();
    }
    public function getIsDefault()
    {
        return $this->data->getIsDefault();
    }
    public function getUserId()
    {
        return $this->data->getUserId();
    }

    public static function fromArray(array $data)
    {

        return new self((new AccountData(
            $data['id'] ?? null,
            $data['bank'],
            $data['agency'],
            $data['number_account'],
            $data['balance'],
            $data['is_default'],
            $data['user_id']
        )));
    }
}
