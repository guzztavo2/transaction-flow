<?php 
namespace App\Domain\DTOs;

final class UserData{

    public function __construct(
    private ?int $id = null, 
    private string $name, 
    private string $email, 
    private string $password, 
    private ?string $created_at = null, 
    private ?string $updated_at = null)
    {
    }

}