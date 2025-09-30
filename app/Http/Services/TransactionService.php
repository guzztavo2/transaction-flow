<?php

namespace App\Http\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Jobs\ResetPasswordJob;
use App\Http\Services\AccountService;
use App\Entities\TransactionEntity;
use App\Entities\AccountEntity;
use App\Jobs\ProcessTransaction;
use App\Models\Account;

class TransactionService extends Service
{
    private AccountService $accountService;
    
    public function __construct(){
        $this->accountService = new AccountService();
    }

    public function store(Request $request)
    {
        $request->validate(['type' => ['required', 'in:0,1,2'], 
        'amount' => ['required', 'decimal:2'], 
        'accountSource' => ['nullable', 'string', 'exists:accounts,id'], 
        'accountDestination' => ['nullable', 'string', 'exists:accounts,id'],
        'scheduled_at' => ['nullable', 'date_format:m-d-Y', 'after_or_equal:now']]);

        if($request['type'] == Transaction::TYPE_TRANSFER)
            return $this->transactionTransfer($request->toArray());
        
        elseif($request['type'] == Transaction::TYPE_DEPOSIT)
            return $this->transactionDeposit($request->toArray());
        
        elseif($request['type'] == Transaction::TYPE_LOOT)
            return $this->transactionLoot($request->toArray());
        else 
            return response()->json(['error' => true, 'message' => 'Transaction type not valid!'], 400);
    }

    private function transactionTransfer(Request|array $request){
        $accountSource = $this->accountService->accountByUser()->where('is_default', true)->first();
        $accountDestination = AccountEntity::findById($request['accountDestination'] ?? '');

        if (empty($accountSource) || empty($accountDestination))
            return response()->json(['error' => true, 'message' => 'For transfer type, accountSource and accountDestination are required!'], 400);
        
        return $this->prepare_response($request, $accountDestination->getAccount(), $accountSource, Transaction::TYPE_TRANSFER);
    }

    private function transactionDeposit(Request|array $request){
        $accountDestination = $this->accountService->accountByUser()->where('is_default', true)->first();

        if(is_null($accountDestination))
            return response()->json(['error' => true, 'message' => 'Account destination not found!'], 400);
        
        return $this->prepare_response($request, $accountDestination, null, Transaction::TYPE_DEPOSIT);
    }
    
    private function transactionLoot(Request|array $request){
        $accountSource = $this->accountService->accountByUser()->where('is_default', true)->first();
        
        if(is_null($accountSource))
            return response()->json(['error' => true, 'message' => 'Account source not found!'], 400);
        
        return $this->prepare_response($request, null, $accountSource, Transaction::TYPE_LOOT);
    }

    private function prepare_response(array $request, string|Account $accountDestination = null, string|Account $accountSource = null, int $transaction_type){
        $scheduled_at = $request['scheduled_at'] ?? null;

        if ($scheduled_at)
            $scheduled_at = Carbon::createFromFormat('m-d-Y', $request['scheduled_at']);

        $transaction = TransactionEntity::create($accountSource, $accountDestination, $transaction_type, $request['amount'], Transaction::STATUS_PENDING, $scheduled_at);
        
        ProcessTransaction::dispatch($transaction->get_id())->delay($scheduled_at ?? now());
        
        $response = [
            'type' => $transaction->get_transaction()->get_type(),
            'amount' => $transaction->get_transaction()->amount,
            'status' => $transaction->get_transaction()->get_status(),
            'scheduled_at ' => $transaction->get_transaction()->scheduled_at ? $transaction->get_transaction()->scheduled_at->format('d-m-Y') : null,
        ];
        return response()->json(array_filter($response, fn($v) => !empty($v)), 201);
    }
}
