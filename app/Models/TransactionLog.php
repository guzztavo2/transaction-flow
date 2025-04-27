<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'message'
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

    protected function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public static function new(string $message)
    {
        return Transaction::create(['message' => $message]);
    }
}
