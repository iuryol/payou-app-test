<?php

namespace App\Interfaces;
use App\Dto\TransactionDto;

interface TransactionRepositoryInterface
{
    public function createNewTransaction(TransactionDto $dto):bool;
}
