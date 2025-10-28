<?php

namespace Tests\Unit;

use App\Entities\UserEntity;
// use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserEntityTest extends TestCase
{
    use RefreshDatabase;

    private array $informations = [
        'name' => 'teste1',
        'email' => 'teste2',
        'password' => 'teste3'
    ];

    private ?UserEntity $user;

    public function __construct(){

    }

    public function test_create_account(): void
    {
        
    }

    #[Test]
    public function test_find_by_id(): void
    {
        $this->test_create_account();
        // $user = UserEntity::findById($this->user->getId());
    }

   
}
