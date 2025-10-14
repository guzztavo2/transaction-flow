<?php 
namespace App\Domain\Actions\Account;

use App\Domain\DTOs\AccountData;
use App\Domain\Entities\Account;
use App\Domain\Repositories\Account\AccountRepositoryInterface;
use App\Exceptions\AccountAlreadyExists;

class CreateAccountAction{
    public function __construct(private AccountRepositoryInterface $repo){}
    
    public function __invoke(AccountData $account){

       $account = Account::fromArray([
            'bank' => $bank, 
            'agency' => $agency, 
            'number_account' => $number_account, 
            'balance' => $balance, 
            'is_default' => $is_default, 
            'user_id' => $user_id
        ]);
        if ($repo->checkIfAlreadyExists($account)){
            throw new AccountAlreadyExists('Account already exists!');
        }
        return $this->repo->save($account);
    }
}