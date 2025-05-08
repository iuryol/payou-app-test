<?php

namespace App\Services;

use App\Dto\TransactionDto;
use App\Enums\StatusType;
use App\Enums\TransactionType;
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
    ) {}
    public function execute($reversal_transaction)
    {
        // não consegui tirar isso daqui
        $sender = $reversal_transaction->receiver;
        $receiver = $reversal_transaction->sender;
        $amount = $reversal_transaction->amount;

        // verifica se transação foi concluida
        if ($reversal_transaction->status !== 'completed') {
            throw new Exception("Transação não concluida não pode ser revertida");
        }
        // verifica se o tipo da transação poder ser revertida
        if ($reversal_transaction->type === 'reversal') {
            throw new Exception("Tipo da transação não permite ser revertida");
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
                $isDebited = $this->userRepository->debitUserAccount($receiver, $amount);
                if (!$isDebited) {
                    throw new Exception("Erro ao processar reversao de debito");
                }
            } catch (Exception $error) {
                $this->transactionRepository->saveAsFailed();
                throw $error;
            }
        }

        if ($reversal_transaction->type === 'transfer') {
            try {
                $this->userRepository->transferAmount($sender,$receiver,$amount);
            } catch (Exception $error) {
                $this->transactionRepository->saveAsFailed();
                throw $error;
            }
        }
        $this->transactionRepository->changeTransactionStatus($reversal_transaction, StatusType::REVERSED->value);
        $this->transactionRepository->saveAsCompleted();
    }
}
