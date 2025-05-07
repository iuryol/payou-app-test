<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface 
{
    public function findByAccountId($id)
    {
        return User::where('account_id',$id)->firstOrFail();
    }
}