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
    protected $fillable = [
        'type',
        'value',
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
        return $this->belongsTo(Account::class, "account_source_id", "id");
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return Account
     */
    public function accountDestination()
    {
        return $this->belongsTo(Account::class, "account_destination_id", "id");
    }
}
