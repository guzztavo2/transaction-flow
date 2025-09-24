<?php

namespace App\Entities;

use App\Models\TransactionLog;
use App\Models\Transaction;

class TransactionLogEntity implements Entity
{
    private ?int $id;
    private string $message;
    private int $transaction_id;
    private ?string $created_at;
    private TransactionLog $transactionLog;
    private Transaction $transaction;

    public function __construct(int $id = null, string $message, int|Transaction $transaction, $created_at = null)
    {
        if (!is_null($id)) 
            $this->setId($id);
        
        $this->setTransaction($transaction);
        $this->setMessage($message);
        $this->created_at = $created_at;
    }

    private function setId(int $id)
    {
        if ($transactionLog = TransactionLog::find($id)) {
            $this->id = $id;
            $this->setTransactionLog($transactionLog);
        }
    }

    private function setMessage(string $message)
    {
        $this->message = $message;
    }

    private function setTransactionLog(TransactionLog|int $transactionLog)
    {   
        if(is_int($transactionLog))
            $transactionLog = TransactionLog::find($transactionLog);
        
        if($transactionLog)
            $this->transactionLog = $transactionLog;
    }

    private function setTransaction(Transaction|int $transaction){
        if(is_int($transaction))
            $transaction = Transaction::find($transaction);

        if($transaction){
            $this->transaction = $transaction;
            $this->transacation_id = $transaction->id;
        }
    }

    private function getTransactionLog(): TransactionLog
    {   
        return $this->transactionLog;
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function save()
    {
        if (is_null($this->getId())) {
            $transactionLog = TransactionLog::create([
                'message' => $this->getMessage(),
                'transaction_id' => $this->transaction_id
            ]);
            $this->setId($transactionLog->id);
        } else {
            $this->transactionLog->update([
                'message' => $this->getMessage(),
                'transaction_id' => $this->transaction_id
            ]);
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'transaction_id' => $this->transaction_id,
            'created_at' => $this->created_at
        ];
    }

    public static function toEntity(TransactionLogEntity $transactionLogEntity): self
    {
        return new self($transactionLogEntity->id, $transactionLogEntity->message, $transactionLogEntity->created_at);
    }

    public static function create(string $message, int|Transaction $transaction, $created_at = null): self
    {
        return (new self(null, $message, $transaction, $created_at))->save();
    }
}
