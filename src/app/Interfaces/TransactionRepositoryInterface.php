<?php

namespace App\Interfaces;
use App\Dto\TransactionDto;
use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function createNewTransaction(TransactionDto $dto):bool;
    public function saveAsCompleted():bool;
    public function saveAsFailed():bool;
    public function saveAsReversed():bool;
    public function getAllReversableTransactions();
    public function changeTransactionStatus(Transaction $transaction , $statusValue);
}
