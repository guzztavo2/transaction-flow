<?php

namespace App\Entities;

use App\Exceptions\UnauthorizedException;
use App\Entities\Entity;
use App\Models\Account;
use App\Models\Transaction;
use App\Entities\TransactionLogEntity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionEntity implements Entity
{
    private ?int $id;
    private int $type;
    private string $amount;
    private int $status;
    private ?Account $accountSource = null;
    private ?Account $accountDestination = null;
    private ?Transaction $transaction;
    private ?Carbon $scheduled_at = null;

    public function __construct(
        ?int $id,
        int $type,
        string $amount,
        int $status,
        ?Carbon $scheduled_at = null,
        Account|string $accountSourceId = null,
        Account|string $accountDestinationId = null
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->set_status($status);
        $this->set_type($type);
        $this->scheduled_at = $scheduled_at;

        if ($accountDestinationId)
            $this->setAccountDestination($accountDestinationId);

        if ($accountSourceId)
            $this->setAccountSource($accountSourceId);
    }

    public function get_id(): ?int
    {
        return $this->id ?? null;
    }

    public function get_transaction(): Transaction
    {
        return $this->transaction;
    }

    public function set_status(int $status){
        if ($status < 0 || $status > Transaction::STATUS_PENDING)
            throw new \UnauthorizedException('Transaction status not valid');

        $this->status = $status;
    }

    public function set_type(int $type){
        if ($type < 0 || $type > Transaction::TYPE_LOOT)
            throw new \UnauthorizedException('Transaction type not valid');

        $this->type = $type;
    }

    public function get_status(): int
    {
        return $this->status;
    }

    public function get_type(): int
    {
        return $this->type;
    }

    public function setAccountSource(Account|string $account)
    {
        if (is_string($account))
            $this->accountSource = Account::find($account);
        else    
            $this->accountSource = $account;
        
        if (is_null($this->accountSource))
            throw new \UnauthorizedException('Account source not found');
    }

    public function setAccountDestination(Account|string $account)
    {
        if (is_string($account))
            $this->accountDestination = Account::find($account);
        else
            $this->accountDestination = $account;

        if (is_null($this->accountDestination))
            throw new \UnauthorizedException('Account destination not found');
    }

    public static function create(int|Account $accountSource = null, int|Account $accountDestination = null, int $type, string $amount, int $status, Carbon $scheduled_at = null): self
    {
        $transaction = (new self(null, $type, $amount, $status, $scheduled_at ,$accountSource, $accountDestination));
        $transaction->save();
        return $transaction;
    }

    public function save()
    {
        if ($this->id) {
            $this->transaction = Transaction::find($this->id);
            if (!$this->transaction) {
                $this->id = null;
                return $this->save();
            }
            TransactionLogEntity::create("UPDATE TRANSACTION: TYPE: {$this->transaction->get_type()}, STATUS: {$this->transaction->get_status()}, AMOUNT: {$this->transaction->amount}", $this->transaction);
            $this->transaction->update([
                'type' => $this->type,
                'amount' => $this->amount,
                'status' => $this->status,
                'scheduled_at' => $this->scheduled_at,
                'account_source_id' => $this->accountSource ? $this->accountSource->id : null,
                'account_destination_id' => $this->accountDestination ? $this->accountDestination->id : null
            ]);
            
            return true;
        } else {
            $this->transaction = Transaction::create([
                'type' => $this->type,
                'amount' => $this->amount,
                'status' => $this->status,
                'scheduled_at' => $this->scheduled_at,
                'account_source_id' => $this->accountSource ? $this->accountSource->id : null,
                'account_destination_id' => $this->accountDestination ? $this->accountDestination->id : null
            ]);
            if ($this->transaction) {
                $this->id = $this->transaction->id;
                TransactionLogEntity::create("CREATE TRANSACTION: TYPE: {$this->transaction->get_type()}, STATUS: {$this->transaction->get_status()}, AMOUNT: {$this->transaction->amount}", $this->transaction);
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
            'amount' => $this->amount,
            'status' => $this->status,
            'scheduled_at' => $this->scheduled_at,
            'account_source_id' => $this->accountSource ? $this->accountSource->id : null,
            'account_destination_id' => $this->accountDestination ? $this->accountDestination->id : null
        ];
    }

    public static function toEntity(Transaction $transaction): self
    {
        return new self($transaction->id, $transaction->type, $transaction->amount, $transaction->status,$transaction->scheduled_at,$transaction->account_source_id, $transaction->account_destination_id);
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

    public static function process(Transaction $transaction){
        $transactionEntity = self::toEntity($transaction);

        DB::transaction(function () use ($transaction, $transactionEntity) {
            try {
                if ($transaction->type === Transaction::TYPE_DEPOSIT) {
                    $transaction->accountDestination->incrementBalance($transaction->amount);
                }

                if ($transaction->type === Transaction::TYPE_LOOT) {
                    if ($transaction->accountSource->balance < $transaction->amount) {
                        throw new \Exception('Insufficient balance');
                    }
                    $transaction->accountSource->decrementBalance($transaction->amount);
                }

                if ($transaction->type === Transaction::TYPE_LOOT && $transaction->accountDestination) {
                    if ($transaction->accountSource->balance < $transaction->amount) {
                        throw new \Exception('Insufficient balance');
                    }
                    $transaction->accountSource->incrementBalance('balance', $transaction->amount);
                    $transaction->accountDestination->decrementBalance('balance', $transaction->amount);
                }

                $transactionEntity->set_status(Transaction::STATUS_DONE);
                $transactionEntity->save();
            } catch (\Throwable $e) {
                $transactionEntity->set_status(Transaction::STATUS_FAIL);
                $transactionEntity->save();
                TransactionLogEntity::create(null, "ERROR TRANSACTION - TYPE: $transaction->get_type() STATUS: $transactionEntity->get_status(), AMOUNT: $transaction->amount - ERROR: $e", $transaction);
            }
        });
    }
}
