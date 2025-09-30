<?php

namespace App\Entities;

use App\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserEntity implements Entity
{
    private ?int $id = null;
    private string $name;
    private string $email;
    private string $password;
    private ?string $created_at;
    private ?string $updated_at;
    private User $user;

    public function __construct(
        int $id = null, string $name, string $email, string $password, string $created_at = null, string $updated_at = null
    ) {
        if (!is_null($id))
            $this->setId($id);

        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setCreatedAt($created_at);
        $this->setUpdatedAt($updated_at);
    }

    private function setId($id)
    {
        if ($user = User::find($id)) {
            $this->id = $id;
            $this->setUser($user);
        }
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }

    private function setEmail(string $email)
    {
        $this->email = $email;
    }

    private function setPassword(string $password)
    {
        $this->password = $password;
    }

    private function setCreatedAt(?string $created_at)
    {
        $this->created_at = $created_at;
    }

    private function setUpdatedAt(?string $updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function updatePassword(string $new_password, string $old_password)
    {
        return $this->user->updatePassword($new_password, $old_password);
    }

    private function setUser(User $user)
    {
        $this->user = $user;
    }

    public function accounts()
    {
        return $this->user->accounts();
    }

    public function save()
    {
        if (is_null($this->getId())) {
            $this->user = User::create([
                'name' => $this->getName(),
                'email' => $this->getEmail(),
                'password' => $this->getPassword()
            ]);

            $this->id = $this->user->id;
        } else {
            $this->user->update([
                'message' => $this->getMessage(),
                'name' => $this->getName(),
                'email' => $this->getEmail(),
                'password' => $this->getPassword()
            ]);
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    public static function create(string $name, string $email, string $password): UserEntity
    {
        $user = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
        return self::toEntity($user);
    }

    public static function findById($id): UserEntity|null
    {
        $user = User::find($id);
        if ($user)
            return new UserEntity($id, $user->name, $user->email, $user->password, $user->created_at, $user->updated_at);

        return null;
    }

    public static function deleteById(int $id): bool
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return true;
        }
        return false;
    }

    private static function toEntity(User $user)
    {
        $userEntity = new UserEntity($user->id, $user->name, $user->email, $user->password, $user->created_at, $user->updated_at);
        return $userEntity;
    }

    public static function query()
    {
        return User::query();
    }
}
