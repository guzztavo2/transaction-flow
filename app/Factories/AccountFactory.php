<?php

namespace App\Factories;

use App\Models\Account;
use Exception;
use Illuminate\Database\Eloquent\Model;

class AccountFactory extends Model
{

    public static function create(
        int $user_id,
        string $bank,
        string $agency,
        string $number_account,
        float $balance
    ) {
        if (is_null(UserFactory::find($user_id)))
            throw new Exception("User not localized");

        return Account::create(
            [
                'user_id' => $user_id,
                'bank' => $bank,
                'agency' => $agency,
                'number_account' => $number_account,
                'balance' => $balance
            ]
        );
    }

    public static function find(int $id)
    {
        return Account::find($id);
    }

    public static function query()
    {
        return Account::query();
    }
}
