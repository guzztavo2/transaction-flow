<?php

namespace App\Domain\Actions\User;

use App\Domain\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use App\Domain\Events\UserLogin;
class LoginAction
{
    public function __construct(private UserRepositoryInterface $repo) {}

    public function __invoke(string $email, string $password, int $expiresAt): false|string
    {
        $credentials = ['email' => $email, 'password' => $password];
        $user = $this->repo->findByEmail($email);
        $expirestAtSeconds = $expiresAt * 60;

        if (!$token = auth('api')->setTTL($expirestAtSeconds)->attempt($credentials))
            return false;

        Redis::setex("user:{$user->getId()}:session", $expirestAtSeconds, $token);
        new UserLogin($user);
        return $token;
    }
}
