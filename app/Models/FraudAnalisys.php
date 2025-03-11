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

    public const STATUS_FAIL = 0, STATUS_DONE = 1, STATUS_PENDING = 2;

    public static function new(
        string $reason,
        int $status
    ) {
        if ($status < 0 || $status > FraudAnalisys::STATUS_PENDING)
            return null;

        return FraudAnalisys::create(
            [
                'reason' => $reason,
                'status' => $status
            ]
        );
    }

}
