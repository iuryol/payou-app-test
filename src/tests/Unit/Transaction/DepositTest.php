<?php
use App\Services\DepositService;
use App\Dto\DepositDto;
use App\DTOs\TransactionDto;
use App\Enums\TransactionType;
use App\Enums\StatusType;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(function () {
    $this->user = Mockery::mock(User::class);
    $this->user->shouldReceive('getAttribute')
        ->with('id')
        ->andReturn(1);

    $this->depositDto = new DepositDto(
        amount: 100,
        description: 'Depósito teste'
    );

    $this->transactionRepository = Mockery::mock(TransactionRepositoryInterface::class);
    $this->userRepository = Mockery::mock(UserRepositoryInterface::class);

    $this->service = new DepositService(
        $this->transactionRepository,
        $this->userRepository
    );
});

test('deve criar a transação e salvar com completar quando  creditar o usuário com sucesso', function () {

    $this->userRepository
        ->shouldReceive('getAuthUser')
        ->once()
        ->andReturn($this->user);

    $this->transactionRepository
        ->shouldReceive('createNewTransaction')
        ->once()
        ->andReturnTrue();

    $this->userRepository
        ->shouldReceive('creditUserAccount')
        ->once()
        ->with($this->user, $this->depositDto->amount);

    $this->transactionRepository
        ->shouldReceive('saveAsCompleted')
        ->once()
        ->andReturnTrue();

    $result = $this->service->execute($this->depositDto);

    expect($result)->toBeTrue();
});

test('não deve creditar o usuário se a criação da transação falhar', function () {
    $this->userRepository
        ->shouldReceive('getAuthUser')
        ->once()
        ->andReturn($this->user);

    $this->transactionRepository
        ->shouldReceive('createNewTransaction')
        ->once()
        ->andReturnFalse();

    $this->userRepository
        ->shouldNotReceive('creditUserAccount');

    $this->transactionRepository
        ->shouldNotReceive('saveAsCompleted');

    $result = $this->service->execute($this->depositDto);

    expect($result)->toBeNull();
});

test('deve marcar a transação como falha e relançar a exceção se o crédito ao usuário falhar', function () {
    $this->userRepository
        ->shouldReceive('getAuthUser')
        ->once()
        ->andReturn($this->user);

    $this->transactionRepository
        ->shouldReceive('createNewTransaction')
        ->once()
        ->andReturnTrue();

    $this->userRepository
        ->shouldReceive('creditUserAccount')
        ->once()
        ->with($this->user, $this->depositDto->amount)
        ->andThrow(new Exception('Erro ao creditar'));

    $this->transactionRepository
        ->shouldReceive('saveAsFailed')
        ->once();

    $this->transactionRepository
        ->shouldNotReceive('saveAsCompleted');

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Erro ao creditar');

    $this->service->execute($this->depositDto);
});
