<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudAnalisys extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reason',
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
            'analyzed_in' => 'datetime',
            'created_at' => 'datetime'
        ];
    }
}
