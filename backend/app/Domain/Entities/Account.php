<?php 
namespace App\Domain\Account\Entities;

use InvalidArgumentException;
use App\Domain\DTOs\AccountData;

final class Account{

    public function __construct(private AccountData $data){
        $this->check_balance();
    }

    public function check_balance(){
        if(bcomp($this->data->balance, '0', 2) < 0)
            throw new InvalidArgumentException('Balance dont be negative.');
    }

    public function check_user_id(){
        $user_id = $this->data->user_id;
        
    }

    public function credit(string $amount){
        $this->data->balance = bcadd($this->data->balance, $amount, 2);
    }

    public function debit(string $amount){
        if (bccomp($this->data->balance, $amount, 2) < 0) 
            throw new \RuntimeException('Insufficient balance');

        $this->data->balance = bcsub($this->data->balance, $amount, 2);
    }

    public function toArray():array{
        
        return [
            'id' => $this->data->id,
            'bank' => $this->data->bank,
            'agency' => $this->data->agency,
            'number_account' => $this->data->number_account,
            'balance' => $this->data->balance,
            'is_default' => $this->data->is_default,
            'user_id' => $this->data->user_id,
        ];
    }

    public static function fromArray(array $data){
        
        return new self((new AccountData($data['id'], $data['bank'], $data['agency'],
                $data['number_account'], $data['balance'], 
                $data['is_default'], $data['user_id'] )));
    }
}