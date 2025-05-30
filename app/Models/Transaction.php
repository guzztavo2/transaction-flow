<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public const STATUS_FAIL = 0;

    public const STATUS_DONE = 1;
    public const STATUS_PENDING = 2;
    public const TYPE_FAIL = 0;
    public const TYPE_DEPOSIT = 1;
    public const TYPE_LOOT = 2;

    protected $fillable = [
        'type',
        'amount',
        'status'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime'
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return Account
     */
    public function accountSource()
    {
        return $this->belongsTo(Account::class, 'account_source_id', 'id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return Account
     */
    public function accountDestination()
    {
        return $this->belongsTo(Account::class, 'account_destination_id', 'id');
    }

    public static function new(
        int|Account $account_source,
        int|Account $account_destination,
        int $type,
        float $value,
        int $status
    ) {
        if ($type < 0 || $type > Transaction::TYPE_LOOT)
            throw new \Exception('Transaction not good selected');

        if ($status < 0 || $status > Transaction::STATUS_PENDING)
            throw new \Exception('Transaction not good selected');

        if (gettype($account_source) == 'int')
            $account_source = Account::find($account_source);
        if (is_null($account_source))
            throw new \Exception('Account not localized');

        if (gettype($account_destination) == 'int')
            $account_destination = Account::find($account_destination);
        if (is_null($account_destination))
            throw new \Exception('Account not localized');

        return Transaction::create(
            [
                'account_source' => $account_source->id,
                'account_destination' => $account_destination->id,
                'type' => $type,
                'value' => $value,
                'status' => $status
            ]
        );
    }
}
