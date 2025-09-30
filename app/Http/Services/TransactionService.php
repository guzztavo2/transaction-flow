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
use App\Jobs\ProcessTransaction;

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
        
        if($request['type'] == Transaction::TYPE_DEPOSIT)
            return $this->transactionDeposit($request->toArray());
        
        if($request['type'] == Transaction::TYPE_LOOT)
            return $this->transactionLoot($request->toArray());
    }

    private function transactionTransfer(Request|array $request){
        if (empty($request['accountSource']) || empty($request['accountDestination']))
            return response()->json(['error' => true, 'message' => 'For transfer type, accountSource and accountDestination are required!'], 400);
        
    }

    private function transactionDeposit(Request|array $request){
        $accountDestination = $this->accountService->accountByUser()->where('is_default', true)->first();

        if(is_null($accountDestination))
            return response()->json(['error' => true, 'message' => 'Account destination not found!'], 400);
        
        $scheduled_at = $request['scheduled_at'] ?? null;

        if ($scheduled_at)
            $scheduled_at = Carbon::createFromFormat('m-d-Y', $request['scheduled_at']);

        $transaction = TransactionEntity::create(null, $accountDestination, Transaction::TYPE_DEPOSIT, $request['amount'], Transaction::STATUS_PENDING, $scheduled_at);
        
        ProcessTransaction::dispatch($transaction->get_id())->delay($scheduled_at ?? now());
        
        $response = [
            'type' => $transaction->get_transaction()->get_type(),
            'amount' => $transaction->get_transaction()->amount,
            'status' => $transaction->get_transaction()->get_status(),
            'scheduled_at ' => $transaction->get_transaction()->scheduled_at ? $transaction->get_transaction()->scheduled_at->format('d-m-Y') : null,
        ];
        return response()->json(array_filter($response, fn($v) => !empty($v)), 201);
    }
    
    private function transactionLoot(Request|array $request){
        $accountSource = $this->accountService->accountByUser()->where('is_default', true)->first();
        
        if(is_null($accountSource))
            return response()->json(['error' => true, 'message' => 'Account source not found!'], 400);
        
        $scheduled_at = $request['scheduled_at'] ?? null;

        if ($scheduled_at)
            $scheduled_at = Carbon::createFromFormat('m-d-Y', $request['scheduled_at']);

        $transaction = TransactionEntity::create($accountSource, null, Transaction::TYPE_LOOT, $request['amount'], Transaction::STATUS_PENDING, $scheduled_at);
        
        ProcessTransaction::dispatch($transaction->get_id())->delay($scheduled_at ?? now());
        
        $response = [
            'type' => $transaction->get_transaction()->get_type(),
            'amount' => $transaction->get_transaction()->amount,
            'status' => $transaction->get_transaction()->get_status(),
            'scheduled_at ' => $transaction->get_transaction()->scheduled_at ? $transaction->get_transaction()->scheduled_at->format('m-d-Y') : null,
        ];
        
        return response()->json(array_filter($response, fn($v) => !empty($v)), 201);
    }
}
