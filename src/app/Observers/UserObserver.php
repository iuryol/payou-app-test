<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user):void
    {
        if (!$user->account_id) {
            do {
                $number = str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
                $accountId = 'ACC-' . $number;
            } while (User::where('account_id', $accountId)->exists());

            $user->account_id = $accountId;
        }
    }
}
