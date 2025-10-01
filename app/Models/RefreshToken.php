<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $fillable = ['id','user_id','token','expires_at'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime:d-m-Y H:i:s', 'expires_at' => 'datetime:d-m-Y H:i:s'];
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
