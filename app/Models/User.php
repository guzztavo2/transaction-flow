<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'user_id');
    }

    public static function new(string $name, string $email, string $password)
    {
        return User::create(['name' => $name, 'email' => $email, 'password' => $password]);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function updatePassword(string $new_password, string $actual_password)
    {
        if (!Hash::check($actual_password, $this->password))
            return ['error' => 'Unauthorized access'];

        if (Hash::check($new_password, $this->password))
            return ['error' => 'You cannot use the same password.'];

        $this->update(['password' => Hash::make($new_password)]);
        return true;
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
