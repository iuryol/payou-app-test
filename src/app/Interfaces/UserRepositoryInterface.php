<?php
namespace App\Interfaces;

interface UserRepositoryInterface 
{
    public function findByAccountId($id);
}