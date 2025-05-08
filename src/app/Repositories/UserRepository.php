<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface 
{
    public function findUserByAccountId($accountId):User
    {
        return User::where('account_id',$accountId)->firstOrFail();
    }

    public function findUserbyId($userId):User
    {
        return User::where('id',$userId)->firstOrFail();
    }

    public function getAuthUser():User
    {
        return Auth::user();
    }

    public function creditUserAccount($user,$amount)
    {
        return DB::transaction(function () use($user,$amount){
            $user->lockForUpdate();
            $user->balance += $amount;
            $user->save();
            return true;
        });
    }

    public function debitUserAccount($user,$amount)
    {
        return DB::transaction(function () use($user,$amount){
            $user->lockForUpdate();
            $user->balance -= $amount;
            $user->save();
            return true;
        });
    }

    public function transferAmount($sender,$receiver,$amount)
    {
        return DB::transaction(function () use($sender, $receiver, $amount){
            $sender->lockForUpdate();
            $sender->balance -= $amount;
            $sender->save();
            $receiver->lockForUpdate();
            $receiver->balance += $amount;
            $receiver->save();
            return true;
        });
    }

    
}