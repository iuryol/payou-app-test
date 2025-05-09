<?php

namespace App\Services;

use App\Dto\TransactionDto;
use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Exceptions\ReversalNotAllowedForIncompleteTransactionException;
use App\Exceptions\ReversalNotAllowedForReversalTypeException;
use App\Interfaces\ReversalServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class ReversalService implements ReversalServiceInterface
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected UserRepositoryInterface $userRepository
    ) {
    }
    public function execute($reversal_transaction)
    {
        // não consegui tirar isso daqui
        $sender = $reversal_transaction->receiver;
        $receiver = $reversal_transaction->sender;
        $amount = $reversal_transaction->amount;

        // verifica se transação foi concluida
        if ($reversal_transaction->status !== 'completed') {
            throw new ReversalNotAllowedForIncompleteTransactionException();
        }
        // verifica se o tipo da transação poder ser revertida
        if ($reversal_transaction->type === 'reversal') {
            throw new ReversalNotAllowedForReversalTypeException();
        }

        $transactionDto =  new TransactionDto(
            amount: $amount,
            type: TransactionType::REVERSAL->value,
            status: StatusType::PENDING->value,
            sender_id: $sender->id,
            receiver_id: $receiver->id,
            reversed_transaction_id:$reversal_transaction->id
        );

        $this->transactionRepository->createNewTransaction($transactionDto);

        if ($reversal_transaction->type === 'deposit') {
        
            try {
                 $this->userRepository->debitUserAccount($receiver, $amount);   
            } catch (Exception $error) {
                $this->transactionRepository->saveAsFailed();
                throw $error;
            }
        }

        if ($reversal_transaction->type === 'transfer') {
            try {
                $this->userRepository->transferAmount($sender, $receiver, $amount);
            } catch (Exception $error) {
                $this->transactionRepository->saveAsFailed();
                throw $error;
            }
        }
        // deixar isso dentro do transaction do proprio repository ?
        $this->transactionRepository->changeTransactionStatus($reversal_transaction, StatusType::REVERSED->value);
        $this->transactionRepository->saveAsCompleted();
    }
}
