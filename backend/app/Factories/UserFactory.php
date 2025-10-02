<?php

namespace App\Factories;

use App\Models\User;

class UserFactory
{
    public static function create(string $name, string $email, string $password)
    {
        return User::create(['name' => $name, 'email' => $email, 'password' => $password]);
    }

    public static function find(int $id)
    {
        return User::find($id);
    }

    public static function query()
    {
        return User::query();
    }
}
