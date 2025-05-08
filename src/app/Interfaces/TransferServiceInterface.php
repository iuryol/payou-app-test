<?php

namespace App\Interfaces;

use App\Dto\TransferDto;
use App\Models\User;

interface TransferServiceInterface
{
    public function execute(TransferDto $dto);
}
