<?php

namespace App\Domain\Actions\User;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Exceptions\UserNotFound;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use App\Domain\Entities\User;

class UpdatePasswordAction
{
    public function __construct(private UserRepositoryInterface $repo) {}

    public function execute(?int $user_id = null, string $new_password, string $old_password): ?User
    {   
        if(is_null($user_id))
            $user_id = auth('api')->user()->id;

        $user = $this->repo->findById($user_id);
            
        if (!$user)
            throw new UserNotFound(message: 'User not found');

        if (!Hash::check($old_password, $user->getPassword()))
            throw new InvalidArgumentException('Old password is incorrect.');

        if (Hash::check($new_password, $user->getPassword()))
            throw new InvalidArgumentException('You cannot use the same password.');

        $user->setPassword(Hash::make($new_password));

        return $this->repo->save($user);
    }
}
