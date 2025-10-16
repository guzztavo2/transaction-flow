<?php

namespace App\Domain\Entities;

use InvalidArgumentException;
use App\Domain\DTOs\AccountData;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Exceptions\UserNotFound;

final class Account
{

    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(private AccountData $data)
    {
        $this->check_balance();
    }

    public function check_balance()
    {
        if (bcomp($this->data->getBalance(), '0', 2) < 0)
            throw new InvalidArgumentException('Balance dont be negative.');
    }

    public function check_user_id()
    {
        $user_id = $this->data->getUserId();
        if (is_null($this->userRepositoryInterface->findById($user_id)))
            throw new UserNotFound();
    }

    public function credit(string $amount)
    {
        $this->data->setBalance(bcadd($this->data->getBalance(), $amount, 2));
    }

    public function debit(string $amount)
    {
        if (bccomp($this->data->getBalance(), $amount, 2) < 0)
            throw new \RuntimeException('Insufficient balance');

        $this->data->setBalance(bcsub($this->data->getBalance(), $amount, 2));
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
            $data['id'],
            $data['bank'],
            $data['agency'],
            $data['number_account'],
            $data['balance'],
            $data['is_default'],
            $data['user_id']
        )));
    }
}
