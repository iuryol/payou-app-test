<?php
namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findUserByAccountId($accountId);
    public function findUserById($userId);
    public function getAuthUser();
    public function creditUserAccount(User $user,float $amount);
    public function debitUserAccount(User $user,float $amount);
    public function transferAmount($sender,$receiver,$amount);
}