<?php

namespace App\Interfaces;

use App\Models\User;

interface TransferServiceInterface
{
    public function execute(User $account_id,User $sender,float $amount);
}
