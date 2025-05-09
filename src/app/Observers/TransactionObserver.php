<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\TransactionLog;

class TransactionObserver
{
    /**
     * Handle the transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        TransactionLog::create([
            'transaction_id' => $transaction->id,
            'action' => $transaction->type,
            'performed_by' => $transaction->sender->id
        ]);
    }

   
}
