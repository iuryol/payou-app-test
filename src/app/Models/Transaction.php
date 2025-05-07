<?php

namespace App\Models;

use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Model;

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
}
