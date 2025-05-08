<?php

namespace App\Interfaces;

use App\Dto\DepositDto;
use App\Models\User;

interface DepositServiceInterface
{
    public function execute(DepositDto $dto);
}
