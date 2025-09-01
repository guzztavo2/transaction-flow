<?php

namespace App\Entities;

use App\Exceptions\UnauthorizedException;
use App\Interfaces\Entity;
use App\Models\FraudAnalisys;

class FraudAnalisysEntity implements Entity
{
    public function __construct(
        private ?int $id,
        private int $transactionId,
        private int $status,
        private string $reason
    ) {
        $this->id = $id;
        $this->transactionId = $transactionId;
        $this->status = $status;
        $this->reason = $reason;
    }

    public static function create(int $status, string $reason)
    {
        return (new self(null, $status, $reason))->save();
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

    public function toArray()
    {
        return [
            'id' => $this->id,
            'transactionId' => $this->transactionId,
            'status' => $this->status,
            'reason' => $this->reason
        ];
    }

    public static function findById(int $id): ?self
    {
        $fraudAnalisys = FraudAnalisys::find($id);
        if (!$fraudAnalisys) {
            return null;
        }
        return self::toEntity($fraudAnalisys);
    }

    public static function query()
    {
        return FraudAnalisys::query();
    }
}
