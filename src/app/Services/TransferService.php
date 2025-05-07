<?php
namespace App\Services;

use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\TransferServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class TransferService implements TransferServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected TransactionRepositoryInterface $transactionRepository
    ){}

    public function execute($receiverAccountId, User $sender,float $amount)
    {
        if ($sender->account_id === $receiverAccountId) {
            throw new Exception("Você não pode transferir para si mesmo.");
        }
        // tentar abstrair usando provider
        $receiver = $this->userRepository->findByAccountId($receiverAccountId);

        if (!$receiver) {
            throw new Exception("Conta de destino não encontrada.");
        }

        if ($sender->balance < $amount) {
            throw new Exception("Saldo insuficiente para a transferência.");
        }

        $transaction = $this->transactionRepository->create([
            'sender_id'     => $sender->id,
            'receiver_id' => $receiver->id,
            'amount'      => $amount,
            'type'        => TransactionType::TRANSFER->value,
            'status'      => StatusType::PENDING->value,
        ]);

        try{
            DB::transaction(function() use ($receiver,$sender,$amount,$transaction){
                $receiver->lockForUpdate();
                $sender->lockForUpdate();
                $sender->balance -= $amount;
                $receiver->balance += $amount;
                $sender->save();
                $receiver->save();
                $transaction->status = StatusType::COMPLETED->value;
                $transaction->save();
            });
        }catch(Exception $error){
            $transaction->status = StatusType::FAILED->value;
            $transaction->save();
            throw $error;
        }
       
    }
}