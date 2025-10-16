<?php 
namespace App\Domain\Actions\User;

use App\Domain\DTOs\UserData;
use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepositoryInterface;

class CreateUserAction{
    public function __construct(private UserRepositoryInterface $repo){}
    
    public function __invoke(UserData $user){

       $user = User::fromArray([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);

        return $this->repo->save($user);
    }
}