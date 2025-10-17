<?php

namespace Tests\Fake;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Exceptions\UnauthorizedException;

class FakeUserRepository implements UserRepositoryInterface
{
    public array $users = [];

    public function findById(int $id): ?User
    {
        $user = array_filter($this->users, fn(User $user) => $user->getId() === $id);
        if (!$user)
            throw new UnauthorizedException('User id not found.');
        $user = $user[0];
        return $user ? User::FromArray($user->toArray()) : null;
    }

    public function save(User $user): ?User
    {
        $data = $user->toArray();
        $dataModel = null;

        if ($data['id']) {
            $model = array_filter($this->users, fn(User $user) => $user->getId() === $data['id']);
            if (!$model)
                throw new UnauthorizedException('User id not found.');

            $model = $model[0];
            $index = array_search($model, $this->users);
            if (!array_key_exists($index, $this->users))
                throw new UnauthorizedException('User id not found.');

            $dataModel = User::FromArray($data);
            $this->users[$index] = $dataModel;
        } else {
            $data['id'] = COUNT($this->users) === 0 ? 1 : COUNT($this->users) - 1;
            $dataModel = User::FromArray($data);
            $this->users[] = $dataModel;
        }

        return $dataModel;
    }
}
