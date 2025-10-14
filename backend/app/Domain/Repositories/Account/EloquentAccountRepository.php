<?php
namespace App\Domain\Repositories\Account;

use App\Domain\Repositories\Account\AccountRepositoryInterface;
use App\Domain\Entities\Account;
use App\Models\Account as AccountModel;

use InvalidArgumentException;

final class EloquentAccountRepository implements AccountRepositoryInterface{

    public function findById(string $id): ?Account{
        $account = AccountModel::find($id);
        return $account ? Account::FromArray($account->toArray()) : null;
    }

    public function save(Account $account): ?Account{
        $data = $account->toArray();
        if($data['id']){
            $model = AccountModel::find($data['id']);
            if(!$model)
                throw new InvalidArgumentException('Account id not found.');
            
            $model->update($data);
        }else{
            $data['id'] = \Str::uuid()->toString();
            $model = AccountModel::create($model->toArray());
        }

        return Account::fromArray($model->toArray());
    }

    private static function query(){
        return AccountModel::query();
    }
}