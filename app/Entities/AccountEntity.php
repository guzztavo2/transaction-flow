<?php

namespace App\Entities;

use App\Models\Account;
use App\Models\User;
use App\Exceptions\UnauthorizedException;

class AccountEntity implements Entity
{
    private ?string $id = null;
    private string $bank;
    private string $agency;
    private string $number_account;
    private float $balance;
    private bool $is_default = false;
    private User $user;
    private ?Account $account;

    public function __construct(
        ?string $id,
        string $bank,
        string $agency,
        string $number_account,
        float $balance,
        bool $is_default = false,
        User|int $user
    ) {
        $this->setId($id);
        $this->setBank($bank);
        $this->setAgency($agency);
        $this->setNumberAccount($number_account);
        $this->setBalance($balance);
        $this->setIsDeault($is_default);
        $this->setUser($user);
    }

    public function setId(?string $id)
    {
        if (empty($id) || !$account = Account::find($id))
            return;

        $this->id = $id;
        $this->account = $account;
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

    public function setIsDeault(?bool $is_default)
    {
        $boolval = boolval($is_default);
        if ($boolval)
            $this->is_default = $boolval;
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

    public function getIsDeault()
    {
        return $this->is_default;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setUser(int|User $user)
    {
        if (is_int($user))
            $this->user = User::find($user);
        else
            $this->user = $user;
    }

    public static function create(string $bank,
        string $agency, string $number_account,
        float $balance, bool $is_default = false, int|User $user)
    {
        $account = new self(null, $bank, $agency, $number_account, $balance, $is_default, $user);
        $account->save();
        return $account;
    }

    public static function toEntity(Account $account): self
    {
        return (new self($account->id, $account->bank, $account->agency, 
        $account->number_account, $account->balance, $account->user_id, $account->is_default));
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
                'is_default' => $this->is_default,
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
                'is_default' => $this->is_default
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
            'is_default' => $this->is_default,
            'user_id' => $this->user->id
        ];
    }

    public static function findById($id): ?self
    {
        $account = Account::find($id);
        if (!$account)
            return null;

        return self::toEntity($account);
    }

    public static function query()
    {
        return Account::query();
    }

    public static function columnKeys(): array
    {
        return [
            'id',
            'bank',
            'agency',
            'number_account',
            'balance',
            'is_default'
        ];
    }
}
