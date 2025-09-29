<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Transaction;
use App\Entities\TransactionEntity;
use App\Entities\TransactionLogEntity;

class ProcessTransaction implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public int $tries = 1;
    /**
     * Create a new job instance.
     */
    public function __construct(public int $transactionId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $transaction = Transaction::find($this->transactionId);
        if(!$transaction)
            throw new \Exception("Transaction not found");
        
        if($this->attempts() >= ($this->tries - 1)){
            throw new \Exception("Max attempts reached");
        }
        if ($transaction->scheduled_at && $transaction->scheduled_at->isFuture()) {
            $this->release(60);
            return;
        }
        
        TransactionEntity::process($transaction);
    }

    public function failed(\Throwable $exception): void
    {
        $transaction = Transaction::find($this->transactionId);
        
        if ($transaction) {
            $transaction->update(['status' => Transaction::STATUS_FAIL]);
            TransactionLogEntity::create("ERROR TRANSACTION - TYPE: {$transaction->get_type()} STATUS: {$transaction->get_status()}, AMOUNT: $transaction->amount - ERROR: {$exception->getMessage()}", $transaction);
        }
    }
}
