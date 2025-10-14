<?php 
namespace App\Domain\Repositories\User;

use App\Domain\Entities\User;

interface UserRepositoryInterface{

    public function findById(int $id): ?User;
    public function save(User $user): ?User;

}