<?php

namespace App\Domain\Actions\User;

use App\Domain\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class RefreshAction
{
    public function __construct(private UserRepositoryInterface $repo) {}

    public function __invoke(string $token, int $max_ttl): false|string
    {
        $user_id = auth('api')->user()->id;
        $key = "user:{$user_id}:session";
        $validToken = Redis::get($key);

        if (!$validToken || $token !== $validToken)
            return false;

        $newToken = auth('api')->setTTL($max_ttl)->refresh();
        Redis::setex($key, $max_ttl, $newToken);
        return $newToken;
    }
}
