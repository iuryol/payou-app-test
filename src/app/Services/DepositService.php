<?php
namespace App\Services;

use App\Dto\DepositDto;
use App\Dto\TransactionDto;
use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Factories\TransactionDtoFactory;
use App\Interfaces\DepositServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Throwable;

class DepositService implements DepositServiceInterface
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected UserRepositoryInterface $userRepository,
        protected TransactionDtoFactory $transactionDtoFactory
    ) {
    }
    public function execute(DepositDto $depositDto)
    {
        $user = $this->userRepository->getAuthUser();

        $transactionDto = $this->transactionDtoFactory->createDepositDto(
            amount: $depositDto->amount,
            userId: $user->id,
            description: $depositDto->description
        );
        
         $isTransactionCreated = $this->transactionRepository->createNewTransaction($transactionDto);
      
        try {
            if($isTransactionCreated) {
                $this->userRepository->creditUserAccount($user, $depositDto->amount);
                return $this->transactionRepository->saveAsCompleted();
            }
        }catch(Throwable $error){
            
            $this->transactionRepository->saveAsFailed();
            throw $error;
        }
        
    }
}
