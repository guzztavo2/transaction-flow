<?php

namespace App\Entities;

class AccountEntity implements Entity
{
    private ?int $id;
    private string $bank;
    private string $agency;
    private string $number_account;
    private float $balance;
    private User $user;
    private ?Account $account;

    public function __construct(
        ?int $id,
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

    public function setUser(int|User $user)
    {
        if (is_int($user))
            $this->user = User::find($user);
        else
            $this->user = $user;
    }

    public static function create(string $bank, string $agency, string $number_account, float $balance, int|User $user)
    {
        (new self(null, $bank, $agency, $number_account, $balance, $user))->save();
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
            Account::create([
                'bank' => $this->bank,
                'agency' => $this->agency,
                'number_account' => $this->number_account,
                'balance' => $this->balance,
                'user_id' => $this->user->id,
            ]);
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
