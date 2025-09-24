<?php

namespace App\Http\Services;

use App\Entities\AccountEntity;
use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountService extends Service
{
    private User $user;

    public function __construct()
    {
        $this->user = Auth('api')->user();
    }

    public function index()
    {
        return response()->json($this
            ->accountByUser()
            ->select(['id', 'bank', 'number_account', 'is_default', 'balance'])
            ->get()
            ->toArray(), 200);
    }

    public function show(string $id)
    {
        $account = $this->accountByUser()->where('id', $id)->firstOrFail();
        return response()->json($account->toArray(), 200);
    }

    private function accountByUser()
    {
        return $this->user->accounts();
    }

    public function store(Request $request)
    {
        $request->validate(['bank' => ['required', 'max:100', 'string'],
            'agency' => ['required', 'max:100', 'string'],
            'number_account' => ['required', 'max:100', 'string']]);

        $accountToBeCreated = array_filter([
            'bank' => $request['bank'],
            'agency' => $request['agency'],
            'number_account' => $request['number_account']
        ], fn($el) => !empty($el));

        if (self::checkAccountExists($accountToBeCreated))
            return response()->json(['error' => true, 'message' => 'Fields already exists in account!']);

        $account = AccountEntity::create($request['bank'],
            $request['agency'], $request['number_account'],
            0, $request['is_default'] ?? false, $this->user);
        return response()->json($account, 200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['bank' => ['nullable', 'max:100', 'string'],
            'agency' => ['nullable', 'max:100', 'string'],
            'number_account' => ['nullable', 'max:100', 'string']]);

        $account = $this->accountByUser()->where('id', $id)->firstOrFail();

        $updated_fields = array_filter([
            'bank' => $request['bank'],
            'agency' => $request['agency'],
            'number_account' => $request['number_account']
        ], fn($el) => !empty($el));

        if (self::checkAccountExists($updated_fields))
            return response()->json(['error' => true, 'message' => 'Fields already exists in account!']);

        $account->update($updated_fields);

        return response()->json($account->toArray(), 200);
    }

    public function delete(string $id)
    {
        $account = $this->accountByUser()->where('id', $id)->firstOrFail();
        if ($account->is_default)
            return response()->json(['error' => true, 'message' => 'Default account cannot be deleted!']);
        $account->delete();
        return response()->json($account->toArray(), 200);
    }

    public function defineDefault(string $id)
    {
        $account = $this->accountByUser()->where('id', $id)->firstOrFail();
        $this->accountByUser()->where('is_default', true)->update(['is_default' => false]);
        $account->update(['is_default' => true]);
        return response()->json($account->toArray(), 200);
    }

    private static function checkAccountExists(array $account_fields)
    {
        $accounts = AccountEntity::query();
        if ($accountExisted = $accounts->where(function ($b) use ($account_fields) {
            if ($account_fields['bank'])
                $b->where('bank', $account_fields['bank']);
            if ($account_fields['agency'])
                $b->where('agency', $account_fields['agency']);
            if ($account_fields['number_account'])
                $b->where('number_account', $account_fields['number_account']);
        })->first())
            if ($accountExisted->bank == $account_fields['bank'] &&
                $accountExisted->agency == $account_fields['agency'] &&
                $accountExisted->number_account == $account_fields['number_account'])
                return true;
        return false;
    }
}
