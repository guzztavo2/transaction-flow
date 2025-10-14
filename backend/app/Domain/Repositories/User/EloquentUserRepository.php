<?php
namespace App\Domain\Repositories\User;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Models\User as UserModel;

use App\Exceptions\UnauthorizedException;

final class EloquentUserRepository implements UserRepositoryInterface{

    public function findById(int $id): ?User{
        $user = UserModel::find($id);
        return $user ? User::FromArray($user->toArray()) : null;
    }

    public function save(User $user): ?User{
        $data = $user->toArray();
        if($data['id']){
            $model = UserModel::find($data['id']);
            if(!$model)
                throw new UnauthorizedException('User id not found.');
            
            $model->update($data);
        }else{
            $data['id'] = \Str::uuid()->toString();
            $model = UserModel::create($model->toArray());
        }

        return User::fromArray($model->toArray());
    }

    private static function query(){
        return UserModel::query();
    }
}