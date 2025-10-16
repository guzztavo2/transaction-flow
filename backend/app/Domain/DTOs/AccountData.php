<?php

namespace App\Domain\DTOs;

final class AccountData
{

    public function __construct(
        private ?string $id = null,
        private string $bank,
        private string $agency,
        private string $number_account,
        private string $balance,
        private bool $is_default = false,
        private int $user_id
    ) {}

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

    public function setBalance(string $balance): void
    {
        $this->balance = $balance;
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

    public function getBalance(): string
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
