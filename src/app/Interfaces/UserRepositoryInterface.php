<?php
namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface 
{
    public function findUserByAccountId($accountId);
    public function findUserById($userId);
    public function getAuthUser():User;
    public function creditUserAccount(User $user,float $amount);
}