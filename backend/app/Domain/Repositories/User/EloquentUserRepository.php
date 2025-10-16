<?php

namespace App\Domain\Repositories\User;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Models\User as UserModel;
use App\Exceptions\UnauthorizedException;

final class EloquentUserRepository implements UserRepositoryInterface
{

    public function findById(int $id): ?User
    {
        $user = UserModel::find($id);
        return $user ? User::FromArray($user->getAttributes()) : null;
    }

    public function save(User $user): ?User
    {
        $data = $user->toArray();
        if ($data['id']) {
            $model = UserModel::find($data['id']);
            if (!$model)
                throw new UnauthorizedException('User id not found.');

            $model->update($data);
        } else
            $model = UserModel::create($data);


        return User::fromArray($model->getAttributes());
    }
    private static function query()
    {
        return UserModel::query();
    }
}
