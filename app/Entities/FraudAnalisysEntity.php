<?php

namespace App\Entities;

use App\Exceptions\UnauthorizedException;
use App\Interfaces\Entity;
use App\Models\FraudAnalisys;

class FraudAnalisysEntity implements Entity
{
    private ?int $id;
    private int $status;
    private string $reason;

    public function __construct(
        ?int $id,
        int $transactionId,
        int $status,
        string $reason
    ) {
        $this->id = $id;
        $this->transactionId = $transactionId;
        $this->status = $status;
        $this->reason = $reason;
    }

    public static function create(int $status, string $reason): self
    {
        return new self(
            null,
            $status,
            $reason
        );
    }

    public static function toEntity(FraudAnalisys $fraudAnalisys): self
    {
        return new self(
            $fraudAnalisys->id,
            $fraudAnalisys->transaction_id,
            $fraudAnalisys->status,
            $fraudAnalisys->reason
        );
    }

    public function save()
    {
        if ($this->id) {
            $fraudAnalisys = FraudAnalisys::find($this->id);
            if (!$fraudAnalisys) {
                throw new UnauthorizedException('Fraud analysis not found');
            }
            $fraudAnalisys->update([
                'status' => $this->status,
                'reason' => $this->reason,
            ]);
        } else {
            FraudAnalisys::create([
                'transaction_id' => $this->transactionId,
                'status' => $this->status,
                'reason' => $this->reason,
            ]);
        }
    }

    public function toArray() {}
    public static function findById(int $id): ?self {}
    public static function query() {}
}
