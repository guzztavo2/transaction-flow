<?php

namespace App\Models;

use App\Factories\Deposit;
use Illuminate\Database\Eloquent\Model;

class DepositFactory
{

    public static function create(float $value, bool $confirmed)
    {
        return Deposit::create(
            [
                'value' => $value,
                'confirmed' => $confirmed
            ]
        );
    }

    public static function find(int $id)
    {
        return Deposit::find($id);
    }

    public static function query()
    {
        return Deposit::query();
    }
}
