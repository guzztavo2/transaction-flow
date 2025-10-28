<?php

namespace App\Domain\Actions\User;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Exceptions\UserNotFound;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use App\Domain\Entities\User;
use App\Domain\Events\UserPasswordUpdated;

class UpdatePasswordAction
{
    public function __construct(private UserRepositoryInterface $repo) {}

    public function execute(?int $user_id = null, string $new_password, string $old_password): ?User
    {
        if (is_null($user_id))
            $user = User::fromArray((auth('api')->user())->getAttributes());
        else
            $user = $this->repo->findById($user_id);

        if (!$user)
            throw new UserNotFound('User not found');

        if (!Hash::check($old_password, $user->getPassword()))
            throw new InvalidArgumentException('Old password is incorrect.');

        if (Hash::check($new_password, $user->getPassword()))
            throw new InvalidArgumentException('You cannot use the same password.');

        $user->setPassword(Hash::make($new_password));

        if ($user = $this->repo->save($user))
            new UserPasswordUpdated($user);
        else
            return null;
        
        return $user;
    }
}
