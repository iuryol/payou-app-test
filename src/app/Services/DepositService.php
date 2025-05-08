<?php
namespace App\Services;

use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Interfaces\DepositServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class DepositService implements DepositServiceInterface
{
    public function __construct(
        protected TransactionRepositoryInterface $repository
        ){}
    public function execute(User $user , float $amount)
    {
        
        $transaction = $this->repository->create([
            'amount' => $amount,
            'type' => TransactionType::DEPOSIT->value,
            'status' => StatusType::PENDING->value,
            'sender_id' => $user->id,
            'receiver_id' => $user->id        
        ]);

      

        try {
            DB::transaction(function () use($user,$amount,$transaction){
                $user->lockForUpdate();
                $user->balance += $amount;
                $user->save();
                $transaction->status = StatusType::COMPLETED->value;
                // refatorar isso , jogar pra o repository ?
                $transaction->save();
            });
        }catch(Throwable $error){
            $transaction->status = StatusType::FAILED->value;
            $transaction->save();
            throw $error;
        }
        
    }
}
