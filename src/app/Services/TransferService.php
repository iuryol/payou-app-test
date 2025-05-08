<?php

namespace App\Services;

use App\Dto\TransactionDto;
use App\Dto\TransferDto;
use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Exceptions\TransferInsufficientBalanceException;
use App\Exceptions\TransferRecipientNotFoundException;
use App\Exceptions\TransferToSelfNotAllowedException;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\TransferServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferService implements TransferServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected TransactionRepositoryInterface $transactionRepository
    ) {}

    public function execute(TransferDto $transferDto)
    {
       
        $sender = $this->userRepository->getAuthUser();

        if ($sender->account_id === $transferDto->receiverAccountId) {
            throw new TransferToSelfNotAllowedException() ;
        }

       

        $receiver = $this->userRepository->findUserByAccountId($transferDto->receiverAccountId);
        
        if (!$receiver) {
            throw new TransferRecipientNotFoundException();
        }

        if ($sender->balance <  $transferDto->amount) {
            throw new TransferInsufficientBalanceException();
        }

        $transactionDto = new TransactionDto(
            amount: $transferDto->amount,
            sender_id: $sender->id,
            receiver_id: $receiver->id,
            type: TransactionType::TRANSFER->value,
            status: StatusType::PENDING->value
        );

        $isTransactionCreated = $this->transactionRepository->createNewTransaction($transactionDto);

        try {
            $this->userRepository->transferAmount($sender, $receiver, $transferDto->amount);
            $this->transactionRepository->saveAsCompleted();
        } catch (Exception $error) {
            $this->transactionRepository->saveAsFailed();
            throw new Exception("Erro ao processar transferencia");
        }
    }
}
