<?php

namespace App\Domain\Actions\User;

use App\Domain\DTOs\UserData;
use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepositoryInterface;

class CreateUserAction
{
    public function __construct(private UserRepositoryInterface $repo) {}

    public function __invoke(UserData $user)
    {
        $user = User::fromArray([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt(),
        ]);

        return $this->repo->save($user);
    }
}
