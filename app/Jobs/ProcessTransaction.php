<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Transaction;
use App\Entities\TransactionEntity;

class ProcessTransaction implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

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
        if(!$transaction){
            return;
        }
        if ($transaction->scheduled_at && $transaction->scheduled_at->isFuture()) {
            $this->release(60);
            return;
        }
        
        TransactionEntity::process($transaction);
    }
}
