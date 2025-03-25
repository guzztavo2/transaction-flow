<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'bank',
        'agency',
        'number_account',
        'balance',
        'user_id'
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
     * Get the model of user
     *
     * @return
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the many transactions
     *
     * @return $this->HasMany
     */
    public function transactionsSource()
    {
        return $this->hasMany(Transaction::class, 'account_source_id', 'id');
    }

    /**
     * Get the many transactions
     *
     * @return $this->HasMany
     */
    public function transactionsDestination()
    {
        return $this->hasMany(Transaction::class, 'account_destination_id', 'id');
    }

    public static function new(int|User $user_id, string $bank, string $agency, string $number_account, float $balance)
    {
        if (gettype($user_id) == 'integer')
            $user_id = User::find($user_id);

        return Account::create(
            [
                'id' => str::uuid()->toString(),
                'user_id' => $user_id->id,
                'bank' => $bank,
                'agency' => $agency,
                'number_account' => $number_account,
                'balance' => $balance
            ]
        );
    }
}
