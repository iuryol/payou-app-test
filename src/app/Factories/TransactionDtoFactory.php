<?php

namespace App\Factories;

use App\Dto\TransactionDto;
use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\ValueObjects\Amount;

class TransactionDtoFactory implements DtoFactoryInterface
{
    public function create(array $data): TransactionDto
    {
        $this->validateRequiredFields($data);

        return new TransactionDto(
            amount: $data['amount'],
            type: $data['type'] ?? TransactionType::TRANSFER->value,
            status: $data['status'] ?? StatusType::PENDING->value,
            sender_id: $data['sender_id'],
            receiver_id: $data['receiver_id'],
            description: $data['description'] ?? null,
            reversed_transaction_id: $data['reversed_transaction_id'] ?? null
        );
    }

    public function createDepositDto(float $amount, int $userId, ?string $description = null): TransactionDto
    {
        return $this->create([
            'amount' => $amount,
            'type' => TransactionType::DEPOSIT->value,
            'sender_id' => $userId,
            'receiver_id' => $userId,
            'description' => $description
        ]);
    }

    public function createTransferDto(float $amount, int $senderId, int $receiverId): TransactionDto
    {
        return $this->create([
            'amount' => $amount,
            'type' => TransactionType::TRANSFER->value,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId
        ]);
    }

    public function createReversalDto(float $amount, int $senderId, int $receiverId, int $reversedTransactionId): TransactionDto
    {
        return $this->create([
            'amount' => $amount,
            'type' => TransactionType::REVERSAL->value,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'reversed_transaction_id' => $reversedTransactionId
        ]);
    }

    private function validateRequiredFields(array $data): void
    {
        $requiredFields = ['amount', 'sender_id', 'receiver_id'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }

        if ($data['amount'] <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }
    }
} 