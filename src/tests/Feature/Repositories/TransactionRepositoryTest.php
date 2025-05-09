<?php 

use App\Dto\TransactionDto;
use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('cria uma nova transação com sucesso', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $dto = new TransactionDto(
        amount: 100,
        type: TransactionType::TRANSFER->value,
        status: StatusType::PENDING->value,
        sender_id: $sender->id,
        receiver_id: $receiver->id
    );

    $repository = new TransactionRepository();
    $success = $repository->createNewTransaction($dto);

    expect($success)->toBeTrue();
    expect(Transaction::first())->not->toBeNull();
});

it('altera o status de uma transação', function () {
    $transaction = Transaction::factory()->create(['status' => StatusType::PENDING->value]);

    $repository = new TransactionRepository();
    $result = $repository->changeTransactionStatus($transaction, StatusType::COMPLETED->value);

    expect($result)->toBeTrue();
    expect($transaction->fresh()->status)->toBe(StatusType::COMPLETED->value);
});

it('salva uma transação como concluída', function () {
    $repository = new TransactionRepository();

    $dto = new TransactionDto(
        amount: 150,
        type: TransactionType::TRANSFER->value,
        status: StatusType::PENDING->value,
        sender_id: User::factory()->create()->id,
        receiver_id: User::factory()->create()->id
    );

    $repository->createNewTransaction($dto);
    $success = $repository->saveAsCompleted();

    expect($success)->toBeTrue();
    expect(Transaction::first()->status)->toBe(StatusType::COMPLETED->value);
});

it('salva uma transação como falhada', function () {
    $repository = new TransactionRepository();

    $dto = new TransactionDto(
        amount: 200,
        type: TransactionType::TRANSFER->value,
        status: StatusType::PENDING->value,
        sender_id: User::factory()->create()->id,
        receiver_id: User::factory()->create()->id
    );

    $repository->createNewTransaction($dto);
    $success = $repository->saveAsFailed();

    expect($success)->toBeTrue();
    expect(Transaction::first()->status)->toBe(StatusType::FAILED->value);
});

it('salva uma transação como revertida', function () {
    $repository = new TransactionRepository();

    $dto = new TransactionDto(
        amount: 200,
        type: TransactionType::TRANSFER->value,
        status: StatusType::PENDING->value,
        sender_id: User::factory()->create()->id,
        receiver_id: User::factory()->create()->id
    );

    $repository->createNewTransaction($dto);
    $success = $repository->saveAsReversed();

    expect($success)->toBeTrue();
    expect(Transaction::first()->status)->toBe(StatusType::REVERSED->value);
});

it('retorna todas as transações reversíveis do usuário autenticado', function () {
    $user = User::factory()->create();
    actingAs($user);

    Transaction::factory()->create([
        'sender_id' => $user->id,
        'status' => StatusType::COMPLETED->value,
        'type' => TransactionType::TRANSFER->value
    ]);

    // Não deve retornar reversals
    Transaction::factory()->create([
        'sender_id' => $user->id,
        'status' => StatusType::COMPLETED->value,
        'type' => TransactionType::REVERSAL->value
    ]);

    $repository = new TransactionRepository();
    $transactions = $repository->getAllReversableTransactions();

    expect($transactions)->toHaveCount(1);
    expect($transactions->first()->type)->toBe(TransactionType::TRANSFER->value);
});
