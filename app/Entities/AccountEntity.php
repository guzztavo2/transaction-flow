<?php

namespace App\Entities;

use App\Models\Account;
use App\Models\User;

class AccountEntity implements Entity
{
    private ?string $id;
    private string $bank;
    private string $agency;
    private string $number_account;
    private float $balance;
    private User $user;
    private ?Account $account;

    public function __construct(
        ?string $id,
        string $bank,
        string $agency,
        string $number_account,
        float $balance,
        User|int $user
    ) {
        $this->id = $id;
        $this->bank = $bank;
        $this->agency = $agency;
        $this->number_account = $number_account;
        $this->balance = $balance;
        $this->setUser($user);
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function setBank(string $bank)
    {
        $this->bank = $bank;
    }

    public function setAgency(string $agency)
    {
        $this->agency = $agency;
    }

    public function setNumberAccount(string $number_account)
    {
        $this->number_account = $number_account;
    }

    public function setBalance(string $balance)
    {
        $this->balance = $balance;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBank()
    {
        return $this->bank;
    }

    public function getAgency()
    {
        return $this->agency;
    }

    public function getNumberAccount()
    {
        return $this->number_account;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setUser(int|User $user)
    {
        if (is_int($user))
            $this->user = User::find($user);
        else
            $this->user = $user;
    }

    public static function create(string $bank, string $agency, string $number_account, float $balance, int|User $user)
    {
        $account = new self(null, $bank, $agency, $number_account, $balance, $user);
        $account->save();
        return $account;
    }

    public function toEntity(Account $account): self
    {
        return new self(
            $account->id,
            $account->bank,
            $account->agency,
            $account->number_account,
            $account->balance,
            $account->user_id
        );
    }

    public function save()
    {
        if ($this->id) {
            $account = Account::find($this->id);
            if (!$account)
                throw new UnauthorizedException('Account not found');

            $account->update([
                'bank' => $this->bank,
                'agency' => $this->agency,
                'number_account' => $this->number_account,
                'balance' => $this->balance,
                'user_id' => $this->user->id,
            ]);
        } else
            $account = Account::create([
                'id' => \Str::uuid()->toString(),
                'bank' => $this->bank,
                'agency' => $this->agency,
                'number_account' => $this->number_account,
                'balance' => $this->balance,
                'user_id' => $this->user->id,
            ]);

        $this->id = $account->id;
        $this->account = $account;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'bank' => $this->bank,
            'agency' => $this->agency,
            'number_account' => $this->number_account,
            'balance' => $this->balance,
            'user_id' => $this->user->id
        ];
    }

    public static function findById(int $id): ?self
    {
        $account = Account::find($id);
        if (!$account)
            return null;

        return $this->toEntity($account);
    }

    public static function query()
    {
        return Account::query();
    }
}
