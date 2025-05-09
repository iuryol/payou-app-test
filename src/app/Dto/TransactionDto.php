<?php

namespace App\Dto;

class TransactionDto
{
    public function __construct(
        public float $amount,
        public string $type,
        public string $status,
        public int $sender_id,
        public int $receiver_id,
        public ?int $reversed_transaction_id = null, 
        public ?string $description = null,
    ) {
    }
}