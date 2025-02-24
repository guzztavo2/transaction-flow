<?php

namespace App\Factories;

use App\Models\Transaction;

class TransactionLogFactory
{
    public static function create(string $message)
    {
        return Transaction::create(['message' => $message]);
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
