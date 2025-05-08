<?php

namespace App\Interfaces;
use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function create(array $dto):Transaction;
}
[ user => id , aop => xpy]