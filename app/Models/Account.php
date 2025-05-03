<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Account extends Model
{
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id', 'bank', 'agency', 'number_account',
        'is_default', 'balance', 'user_id'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:d-m-Y H:i:s'
        ];
    }

    /**
     * Get the model of user
     *
     * @return
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
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

        $account = Account::create(['id' => Str::uuid()->toString(), 'bank' => $bank, 'agency' => $agency,
            'number_account' => $number_account, 'balance' => $balance, 'user_id' => $user_id->id]);

        return $account;
    }
}
