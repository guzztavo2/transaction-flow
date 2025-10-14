<?php 
namespace App\Domain\DTOs;

final class AccountData{

    public function __construct(
    private ?string $id = null, 
    private string $bank, 
    private string $agency, 
    private string $number_account, 
    private string $balance, 
    private bool $is_default = false, 
    private int $user_id)
    {
    }
}