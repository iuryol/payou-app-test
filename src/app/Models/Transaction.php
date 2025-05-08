<?php

namespace App\Models;

use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([TransactionObserver::class])]
class Transaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'sender_id',
        'receiver_id',
        'reversed_transaction_id',
        'status',
        'description'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id');
    }
}
