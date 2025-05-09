<?php

use App\Services\ReversalService;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Exceptions\ReversalNotAllowedForIncompleteTransactionException;
use App\Exceptions\ReversalNotAllowedForReversalTypeException;
use App\Enums\TransactionType;
use App\Enums\StatusType;
use App\Models\Transaction;
use App\Dto\TransactionDto;
use Mockery;

beforeEach(function () {
    $this->sender = Mockery::mock(\App\Models\User::class)->makePartial()->shouldIgnoreMissing();
    $this->sender->id = 1;

    $this->receiver = Mockery::mock(\App\Models\User::class)->makePartial()->shouldIgnoreMissing();
    $this->receiver->id = 2;

    $this->transactionRepository = Mockery::mock(TransactionRepositoryInterface::class);
    $this->userRepository = Mockery::mock(UserRepositoryInterface::class);

    $this->service = new ReversalService(
        $this->transactionRepository,
        $this->userRepository
    );
});

test('deve reverter transação do tipo deposit com sucesso', function () {
    $tx = makeTransaction('deposit', 'completed', 200);
    $this->transactionRepository->shouldReceive('createNewTransaction')
        ->once()
        ->with(Mockery::on(function ($dto) use ($tx) {
            return $dto instanceof TransactionDto
                && $dto->amount === $tx->amount
                && $dto->type === 'reversal'
                && $dto->status === 'pending'
                && $dto->sender_id === $tx->receiver->id
                && $dto->receiver_id === $tx->sender->id
                && $dto->reversed_transaction_id === $tx->id;
        }));


    $this->userRepository->shouldReceive('debitUserAccount')->once()->with($this->sender, 200);
    $this->transactionRepository->shouldReceive('changeTransactionStatus')->once()->with($tx, 'reversed');
    $this->transactionRepository->shouldReceive('saveAsCompleted')->once();

    $this->service->execute($tx);
});

test('deve reverter transação do tipo transfer com sucesso', function () {
    $tx = makeTransaction('transfer', 'completed', 500);

    $this->transactionRepository->shouldReceive('createNewTransaction')->once();
    $this->userRepository->shouldReceive('transferAmount')->once()->with($this->receiver, $this->sender, 500);
    $this->transactionRepository->shouldReceive('changeTransactionStatus')->once();
    $this->transactionRepository->shouldReceive('saveAsCompleted')->once();

    $this->service->execute($tx);
});

test('deve lançar exceção se transação não estiver completed', function () {
    $tx = makeTransaction('deposit', 'pending', 100);
    expect(fn() => $this->service->execute($tx))
        ->toThrow(ReversalNotAllowedForIncompleteTransactionException::class);
});

test('deve lançar exceção se transação for reversal', function () {
    $tx = makeTransaction('reversal', 'completed', 100);
    expect(fn() => $this->service->execute($tx))
        ->toThrow(ReversalNotAllowedForReversalTypeException::class);
});

test('deve marcar como failed e relançar erro se falhar reversão de deposito', function () {
    $tx = makeTransaction('deposit', 'completed', 300);

    $this->transactionRepository->shouldReceive('createNewTransaction')->once();
    $this->userRepository->shouldReceive('debitUserAccount')->once()->andThrow(new Exception('erro'));
    $this->transactionRepository->shouldReceive('saveAsFailed')->once();

    expect(fn() => $this->service->execute($tx))
        ->toThrow(Exception::class, 'erro');
});

test('deve marcar como failed e relançar erro se falhar reversão de transfer', function () {
    $tx = makeTransaction('transfer', 'completed', 400);

    $this->transactionRepository->shouldReceive('createNewTransaction')->once();
    $this->userRepository->shouldReceive('transferAmount')->once()->andThrow(new Exception('erro'));
    $this->transactionRepository->shouldReceive('saveAsFailed')->once();

    expect(fn() => $this->service->execute($tx))
        ->toThrow(Exception::class, 'erro');
});

function makeTransaction(string $type, string $status, float $amount): Transaction
{
    $tx = Mockery::mock(Transaction::class)->makePartial()->shouldIgnoreMissing();
    $tx->id = rand(1, 999);
    $tx->type = $type;
    $tx->status = $status;
    $tx->amount = $amount;
    $tx->sender = test()->sender;
    $tx->receiver = test()->receiver;
    return $tx;
}
