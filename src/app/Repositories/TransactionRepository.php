<?php

namespace App\Repositories;

use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function getAllReversableTransactions()
    {
        return Transaction::where('status', StatusType::COMPLETED->value)
            ->whereNot('type', TransactionType::REVERSAL->value)
            ->get();
    }
}
