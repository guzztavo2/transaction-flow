<?php

namespace App\Domain\DTOs;
use Carbon\Carbon;

final class UserData
{

    public function __construct(
        private ?int $id = null,
        private string $name,
        private string $email,
        private string $password,
        private ?Carbon $created_at = null,
        private ?Carbon $updated_at = null
    ) {}

    public function getId(): int|null
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
    public function getCreatedAt(): Carbon|null
    {
        return $this->created_at;
    }
    public function getUpdatedAt(): Carbon|null
    {
        return $this->updated_at;
    }

    public function setId(?int $id = null): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setCreatedAt(?Carbon $created_at = null): void
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt(?Carbon $updated_at = null): void
    {
        $this->updated_at = $updated_at;
    }
}
