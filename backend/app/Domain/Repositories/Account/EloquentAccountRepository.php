<?php

namespace App\Domain\Repositories\Account;

use App\Domain\Repositories\Account\AccountRepositoryInterface;
use App\Domain\Entities\Account;
use App\Models\Account as AccountModel;
use Illuminate\Support\Str;

use InvalidArgumentException;

final class EloquentAccountRepository implements AccountRepositoryInterface
{

    public function findById(string $id): ?Account
    {
        $account = AccountModel::find($id);
        return $account ? Account::FromArray($account->toArray()) : null;
    }

    public function save(Account $account): ?Account
    {
        $data = $account->toArray();
        if ($data['id']) {
            $model = AccountModel::find($data['id']);
            if (!$model)
                throw new InvalidArgumentException('Account id not found.');

            $model->update($data);
        } else {
            $data['id'] = Str::uuid()->toString();
            $model = AccountModel::create($data);
        }

        return Account::fromArray($model->toArray());
    }

    public function checkIfAlreadyExists(Account $account): bool
    {
        return self::query()
            ->where('bank', $account_fields['bank'] ?? null)
            ->where('agency', $account_fields['agency'] ?? null)
            ->where('number_account', $account_fields['number_account'] ?? null)
            ->exists();
    }

    private static function query()
    {
        return AccountModel::query();
    }
}
