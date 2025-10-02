<?php

namespace App\Factories;

use App\Models\Transaction;
use Exception;

class TransactionFactory
{
    public const TYPE_FAIL = 0, TYPE_DEPOSIT = 1, TYPE_LOOT = 2;
    public const STATUS_FAIL = 0, STATUS_DONE = 1, STATUS_PENDING = 2;

    public static function create(
        int $account_source,
        int $account_destination,
        int $type,
        float $value,
        int $status
    ) {
        if ($type < 0 || $type > TransactionFactory::TYPE_LOOT)
            throw new Exception("Transaction not good selected");

        if ($status < 0 || $status > TransactionFactory::STATUS_PENDING)
            throw new Exception("Transaction not good selected");

        $account_source = self::find($account_source)->first();
        if (is_null($account_source))
            throw new Exception("Account not localized");

        $account_destination = self::find($account_destination)->first();
        if (is_null($account_destination))
            throw new Exception("Account not localized");

        return Transaction::create(
            [
                'account_source' => $account_source,
                'account_destination' => $account_destination,
                'type' => $type,
                'value' => $value,
                'status' => $status
            ]
        );
    }

    public static function find(int $id)
    {
        return Transaction::find($id);
    }

    public static function query()
    {
        return Transaction::query();
    }
}
