<?php
namespace App\Services;

use App\Models\User;

class AccountIdGenerator
{
    public static function execute(): string
    {
        do {
            $number = str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            $accountId = 'ACC-' . $number;
        } while (User::where('account_id', $accountId)->exists());

        return $accountId;
    }
}
