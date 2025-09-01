<?php

namespace App\Entities;

use App\Exceptions\UnauthorizedException;
use App\Interfaces\Entity;
use App\Models\Account;
use App\Models\Transaction;

class TransactionEntity implements Entity
{
    private ?int $id;
    private int $type;
    private float $value;
    private int $status;
    private Account $accountSource;
    private Account $accountDestination;
    private ?Transaction $transaction;

    public function __construct(
        ?int $id,
        int $type,
        float $value,
        int $status,
        Account|int $accountSourceId = null,
        Account|int $accountDestinationId
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
        $this->status = $status;
        $this->setAccountDestination($accountDestinationId);

        if ($accountSourceId)
            $this->setAccountSource($accountSourceId);
    }

    public function setAccountSource(Account|int $account)
    {
        if (is_int($account))
            $this->accountSource = Account::find($account);
        if (is_null($this->accountSource))
            throw new \UnauthorizedException('Account source not found');
    }

    public function setAccountDestination(Account|int $account)
    {
        if (is_int($account))
            $this->accountSource = Account::find($account);
        if (is_null($this->accountSource))
            throw new \UnauthorizedException('Account destination not found');
    }

    public static function create(int|Account $accountSource, int|Account $accountDestination, int $type, float $value, int $status): self
    {
        if ($type < 0 || $type > Transaction::TYPE_LOOT)
            throw new \UnauthorizedException('Transaction type not valid');

        if ($status < 0 || $status > Transaction::STATUS_PENDING)
            throw new \UnauthorizedException('Transaction status not valid');

        $this->setAccountSource($accountSource);

        $this->setAccountDestination($accountDestination);

        return (new self(null, $type, $value, $status, $this->accountSource, $this->accountDestination))->save();
    }

    public function save()
    {
        if ($this->id) {
            $this->transaction = Transaction::find($this->id);
            if (!$this->transaction) {
                $this->id = null;
                return $this->save();
            }

            $this->transaction->update([
                'type' => $this->type,
                'value' => $this->value,
                'status' => $this->status,
                'account_source_id' => $this->accountSource->id,
                'account_destination_id' => $this->accountDestination->id
            ]);

            return true;
        } else {
            $this->transaction = Transaction::create([
                'type' => $this->type,
                'value' => $this->value,
                'status' => $this->status,
                'account_source_id' => $this->accountSource->id,
                'account_destination_id' => $this->accountDestination->id
            ]);
            if ($this->transaction) {
                $this->id = $this->transaction->id;
                return true;
            }
        }
        return false;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'status' => $this->status,
            'account_source_id' => $this->accountSource->id,
            'account_destination_id' => $this->accountDestination->id
        ];
    }

    public static function toEntity(Transaction $transaction): self
    {
        return new self($transaction->id, $transaction->type, $transaction->value, $transaction->status, $transaction->account_source_id, $transaction->account_destination_id);
    }

    public static function findById(int $id): ?self
    {
        $transaction = Transaction::find($id);
        if ($transaction)
            return self::toEntity($transaction);

        return null;
    }

    public static function query()
    {
        return Transaction::query();
    }
}
