<?php
namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface 
{
    public function findUserByAccountId($accountId):User;
    public function findUserById($userId):User;
    public function getAuthUser():User;
    public function creditUserAccount(User $user,float $amount);
}