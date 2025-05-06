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


    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
