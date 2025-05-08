<?php

namespace App\Services;

use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Interfaces\ReversalServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class ReversalService implements ReversalServiceInterface
{
    public function __construct(
        protected TransactionRepositoryInterface $repository
    ) {}
    public function execute($reversal_transaction)
    {
       
        $sender = $reversal_transaction->sender;
        $amount = $reversal_transaction->amount;

       
        // verifica se transação foi concluida
        if ($reversal_transaction->status !== 'completed') {
            throw new Exception("Transação não pode ser revertida");
        }

        if ($reversal_transaction->type === 'reversal') {
            throw new Exception("Transação não pode ser revertida");
        }
            $transaction = $this->repository->create([
                'sender_id'     => $sender->id,
                'amount'      => $amount,
                'type'        => TransactionType::REVERSAL->value,
                'status'      => StatusType::PENDING->value,
                'reversed_transaction_id' => $reversal_transaction->id
            ]);

        
            if ($reversal_transaction->type === 'deposit') {
                try{
                    DB::transaction(function () use ($sender, $amount, $reversal_transaction) {
                        $sender->lockForUpdate();
                        $reversal_transaction->lockForUpdate();
                        $sender->balance -= $amount;
                        $sender->save();
                        $reversal_transaction->status = StatusType::REVERSED->value;
                        $reversal_transaction->save();
                    });
                    $transaction->status = StatusType::COMPLETED->value;
                    $transaction->save();
                }catch(Exception $error){
                    $transaction->status = StatusType::FAILED->value;
                    $transaction->save();
                    throw $error;
                }
            }
 
    }
}
