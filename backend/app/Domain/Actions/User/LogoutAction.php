<?php

namespace App\Domain\Actions\User;

use Illuminate\Support\Facades\Redis;

class LogoutAction
{

    public function __invoke(): void
    {
        $user = auth('api')->user();

        if ($user) {
            Redis::del("user:{$user->id}:session");
            auth('api')->logout();
        }
    }
}
