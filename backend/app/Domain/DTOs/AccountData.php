<?php

namespace App\Domain\DTOs;
use App\Domain\ValueObjects\Money;

final class AccountData
{

    public function __construct(
        private ?string $id = null,
        private string $bank,
        private string $agency,
        private string $number_account,
        private Money|string $balance,
        private bool $is_default = false,
        private int $user_id
    ) {
        $this->setBalance($balance);
    }

    #Setters

    public function setId(?string $id = null): void
    {
        $this->id = $id;
    }

    public function setBank(string $bank): void
    {
        $this->bank = $bank;
    }

    public function setAgency(string $agency): void
    {
        $this->agency = $agency;
    }

    public function setNumberAccount(string $number_account): void
    {
        $this->number_account = $number_account;
    }

    public function setBalance(string|Money $balance): void
    {
        if($balance instanceof Money)
            $this->balance = $balance;
        else
            $this->balance = new Money($balance);
    }

    public function setIsDefault(string $is_default): void
    {
        $this->is_default = $is_default;
    }

    public function setUserId(int $id): void
    {
        $this->id = $id;
    }

    //Getters
    public function getId(): string|null
    {
        return $this->id;
    }

    public function getBank(): string
    {
        return $this->bank;
    }

    public function getAgency(): string
    {
        return $this->agency;
    }

    public function getNumberAccount(): string
    {
        return $this->number_account;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function getIsDefault(): bool
    {
        return $this->is_default;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }
}
