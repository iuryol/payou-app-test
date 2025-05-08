<?php

namespace App\Dto;

class DepositDto 
{
    public function __construct(
        public float $amount,
        public ?string $description,
    ) {
    }
}