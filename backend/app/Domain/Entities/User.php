<?php

namespace App\Domain\Entities;

use App\Domain\DTOs\UserData;

final class User
{

    public function __construct(private UserData $data) {}

    public function toArray(): array
    {

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }

    public static function fromArray(array $data)
    {
        return new self((new UserData($data['id'] ?? null, $data['name'], $data['email'], $data['password'], $data['created_at'] ?? null, $data['updated_at'] ?? null)));
    }

    public function getId(): int|null
    {
        return $this->data->getId();
    }

    public function getName(): string
    {
        return $this->data->getName();
    }

    public function getEmail(): string
    {
        return $this->data->getEmail();
    }

    public function getPassword(): string
    {
        return $this->data->getPassword();
    }

    public function getCreatedAt(): string|null
    {
        return $this->data->getCreatedAt();
    }

    public function getUpdatedAt(): string|null
    {
        return $this->data->getUpdatedAt();
    }

    public function setId(?int $id = null): void
    {
        $this->data->setId($id);
    }

    public function setName(string $name): void
    {
        $this->data->setName($name);
    }

    public function setEmail(string $email): void
    {
        $this->data->setEmail($email);
    }

    public function setPassword(string $password): void
    {
        $this->data->setPassword($password);
    }

    public function setCreatedAt(?string $createdAt = null): void
    {
        $this->data->setCreatedAt($createdAt);
    }

    public function setUpdatedAt(?string $updatedAt = null): void
    {
        $this->data->setUpdatedAt($updatedAt);
    }
}
