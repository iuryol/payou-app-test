<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Enums\TransactionType;
use App\Enums\StatusType;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(
                [
                TransactionType::TRANSFER->value,
                TransactionType::DEPOSIT->value,
                TransactionType::REVERSAL->value,
                ]
            ),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'reversed_transaction_id' => null, // Pode ser sobrescrito em testes específicos
            'status' => $this->faker->randomElement(
                [
                StatusType::PENDING->value,
                StatusType::COMPLETED->value,
                StatusType::FAILED->value,
                StatusType::REVERSED->value,
                ]
            ),
            'description' => $this->faker->sentence(),
        ];
    }

    public function reversed(): static
    {
        return $this->state(
            fn () => [
            'status' => StatusType::REVERSED->value,
            ]
        );
    }

    public function completed(): static
    {
        return $this->state(
            fn () => [
            'status' => StatusType::COMPLETED->value,
            ]
        );
    }
}

