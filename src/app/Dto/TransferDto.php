<?php

namespace App\Dto;

class TransferDto
{
    public function __construct(
        public string $receiverAccountId,
        public float $amount,
        public ?string $description = null
    ) {   
    }
}