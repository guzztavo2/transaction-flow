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

    public function test_create_user(): void
    {
        $user = UserEntity::create($this->informations['name'], $this->informations['email'], $this->informations['password']);
        $this->assertInstanceOf(UserEntity::class, $user);
        $this->user = $user;
    }

    #[Test]
    public function test_find_by_id(): void
    {
        $this->test_create_user();
        $user = UserEntity::findById($this->user->getId());
        $this->assertInstanceOf(UserEntity::class, $user);
    }

    public function test_update_user_password(): void
    {
        $this->test_create_user();
        $user = UserEntity::findById($this->user->getId());
        $this->assertInstanceOf(UserEntity::class, $user);
        $this->assertTrue($user->updatePassword('nova_senha', $this->informations['password']));
        $this->assertTrue(Hash::check('nova_senha', $user->getPassword()));
    }
}
