<?php

namespace App\Interfaces;

use App\Models\User;

interface ReversalServiceInterface
{
    public function execute($id);
}
