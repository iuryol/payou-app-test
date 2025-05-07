<?php
 namespace App\Repositories ;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;

 class TransactionRepository implements TransactionRepositoryInterface {
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }
 }