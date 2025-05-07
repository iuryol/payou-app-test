<?php

namespace App\Observers;

use App\Models\transaction;

class TransactionObserver
{
    /**
     * Handle the transaction "created" event.
     */
    public function created(transaction $transaction): void
    {
        dd('teste');

    }

    /**
     * Handle the transaction "updated" event.
     */
    public function updated(transaction $transaction): void
    {
        //
    }

    /**
     * Handle the transaction "deleted" event.
     */
    public function deleted(transaction $transaction): void
    {
        //
    }

    /**
     * Handle the transaction "restored" event.
     */
    public function restored(transaction $transaction): void
    {
        //
    }

    /**
     * Handle the transaction "force deleted" event.
     */
    public function forceDeleted(transaction $transaction): void
    {
        //
    }
}
