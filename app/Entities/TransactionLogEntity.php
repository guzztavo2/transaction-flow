<?php

namespace App\Entities;

class TransactionLogEntity implements Entity
{
    private ?int $id;
    private string $message;
    private ?string $created_at;
    private TransactionLog $transactionLog;

    public function __construct(int $id = null, string $message, $created_at = null)
    {
        if (!is_null($id)) {
            $transactionLog = TransactionLog::find($id);
            if ($transactionLog) {
                $this->setId($id);
            }
        }
        $this->setMessage($message);
        $this->created_at = $created_at;
    }

    private function setId(int $id)
    {
        if ($transactionLog = TransactionLog::find($id)) {
            $this->id = $id;
            $this->setTransaction($transactionLog);
        }
    }

    private function setMessage(string $message)
    {
        $this->message = $message;
    }

    private function setTransaction(TransactionLog $transactionLog)
    {
        $this->transactionLog = $transactionLog;
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
            ]);
            $this->setId($transactionLog->id);
        } else {
            $this->transactionLog->update([
                'message' => $this->getMessage(),
            ]);
        }
    }

    public static function toEntity(TransactionLogEntity $transactionLogEntity): self
    {
        return new self(
            $transactionLogEntity->id,
            $transactionLogEntity->message,
            $transactionLogEntity->created_at
        );
    }

    public static function create() {}
}
