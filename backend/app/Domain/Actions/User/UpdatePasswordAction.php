<?php 
namespace App\Domain\Actions\User;

use App\Domain\DTOs\UserData;
use App\Domain\Entities\User;
use App\Domain\Repositories\User\AccountRepositoryInterface;
use App\Exceptions\UserNotFound;
use Illuminate\Support\Facades\Hash;

use InvalidArgumentException;

class CreateUserAction{
    public function __construct(private AccountRepositoryInterface $repo){}
    
    public function execute(int $user_id, ){

        $user = $this->repo->findById($user_id);

        if(!$user)
            throw new UserNotFound('User not found');

        if (!Hash::check($old_password, $user->password))
            throw new InvalidArgumentException('Old password is incorrect.');

        if (Hash::check($new_password, $user->password))
            throw new InvalidArgumentException('You cannot use the same password.');

        $user->password = Hash::make($new_password);

        return $this->repo->save($user);
    }
}