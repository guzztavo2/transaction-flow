<?php

namespace App\Factories;

use Exception;
use App\Models\FraudAnalisys;

class FraudAnalisysFactory
{
    public const STATUS_FAIL = 0, STATUS_DONE = 1, STATUS_PENDING = 2;

    public static function create(
        string $reason,
        int $status
    ) {
        if ($status < 0 || $status > TransactionFactory::STATUS_PENDING)
            throw new Exception("Transaction not good selected");

        return FraudAnalisys::create(
            [
                'reason' => $reason,
                'status' => $status
            ]
        );
    }

    public static function find(int $id)
    {
        return FraudAnalisys::find($id);
    }

    public static function query()
    {
        return FraudAnalisys::query();
    }
}
