<?php

namespace App\Repositories;

use App\Dto\TransactionDto;
use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionRepository implements TransactionRepositoryInterface {
    private Transaction $transaction;
    public function createNewTransaction(TransactionDto $transcationDto):bool
    {
        $this->transaction = Transaction::create([
                'amount' => $transcationDto->amount,
                'type' => $transcationDto->type,
                'status' => $transcationDto->status,
                'sender_id' => $transcationDto->sender_id,
                'receiver_id' => $transcationDto->receiver_id,
                'description' => $transcationDto->description,
                'reversed_transaction_id' => $transcationDto->reversed_transaction_id,
            ]);

            if(!isset($this->transaction)){
                return false;
            }
            return true;
    }

    public function changeTransactionStatus(Transaction $transaction , $statusValue)
    {
        $transaction->status = $statusValue ;
        return $transaction->save();
    }

    public function saveAsCompleted(): bool
    {
        $this->transaction->status = StatusType::COMPLETED->value;
        return $this->transaction->save();
    }
    public function saveAsFailed(): bool
    {
        $this->transaction->status = StatusType::FAILED->value;
        return $this->transaction->save();
    }
    public function saveAsReversed(): bool
    {
        $this->transaction->status = StatusType::REVERSED->value;
        return $this->transaction->save();
    }

    public function getAllReversableTransactions()
    {
        return Transaction::where('sender_id', Auth::user()->id)->where('status', StatusType::COMPLETED->value)
        ->whereNot('type', TransactionType::REVERSAL->value)
        ->get();
    }
}
