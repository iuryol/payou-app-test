<?php

namespace App\Interfaces;

use App\Models\User;

interface DepositServiceInterface
{
    public function execute(User $user , float $amount);
}
