<?php

namespace App\Factories;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'bank',
        'agency',
        'number_account',
        'balance'
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
     * @return User
     */
    public function user()
    {
        return $this->belongsToMany(User::class, "user_id", "id");
    }
}
