<?php
use App\Services\TransferService;
use App\Dto\TransferDto;
use App\Dto\TransactionDto;
use App\Enums\TransactionType;
use App\Enums\StatusType;
use App\Exceptions\TransferInsufficientBalanceException;
use App\Exceptions\TransferRecipientNotFoundException;
use App\Exceptions\TransferToSelfNotAllowedException;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Mockery;
use Exception;

beforeEach(function () {
    $this->sender = Mockery::mock(User::class);
    $this->sender->shouldReceive('getAttribute')
        ->with('id')
        ->andReturn(1);

    $this->sender->shouldReceive('getAttribute')
        ->with('balance')
        ->andReturn(1000);

    $this->sender->shouldReceive('getAttribute')
        ->with('account_id')
        ->andReturn('100');

    $this->receiver = Mockery::mock(User::class);
    $this->receiver->shouldReceive('getAttribute')
        ->with('id')
        ->andReturn(2);
    $this->receiver->shouldReceive('getAttribute')
        ->with('account_id')
        ->andReturn(200);
    $this->transactionRepository = Mockery::mock(TransactionRepositoryInterface::class);
    $this->userRepository = Mockery::mock(UserRepositoryInterface::class);

    $this->service = new TransferService(
        $this->userRepository,
        $this->transactionRepository
    );
});

test('deve concluir a transferência e criar a transação quando saldo for suficiente e destinatário for válido', function () {
       $transferDto = new TransferDto(
        amount: 50,
        receiverAccountId: 200
        );
      
    $this->userRepository
        ->shouldReceive('getAuthUser')
        ->once()
        ->andReturn($this->sender);

    $this->userRepository
        ->shouldReceive('findUserByAccountId')
        ->once()
        ->with(200)
        ->andReturn($this->receiver);

    $this->transactionRepository
        ->shouldReceive('createNewTransaction')
        ->once()
        ->andReturnTrue();

    $this->userRepository
        ->shouldReceive('transferAmount')
        ->once()
        ->with($this->sender, $this->receiver, $transferDto->amount);

    $this->transactionRepository
        ->shouldReceive('saveAsCompleted')
        ->once()
        ->andReturnTrue();

    $result = $this->service->execute($transferDto);

    expect($result)->toBeNull();
});

test('deve lançar exceção TransferToSelfNotAllowedException quando o usuário tentar transferir para sua própria conta', function () {
    
    $transferDto = new TransferDto(
        amount: 50,
        receiverAccountId: 100
    );
    
       
    $this->userRepository
        ->shouldReceive('getAuthUser')
        ->once()
        ->andReturn($this->sender);

    
    $this->expectException(TransferToSelfNotAllowedException::class);
    $this->service->execute($transferDto);
});

test('deve lançar exceção TransferRecipientNotFoundException quando o destinatário não for encontrado', function () {
      $transferDto = new TransferDto(
        amount: 50,
        receiverAccountId: 201
        );

    $this->userRepository
        ->shouldReceive('getAuthUser')
        ->once()
        ->andReturn($this->sender);

    $this->userRepository
        ->shouldReceive('findUserByAccountId')
        ->once()
        ->with(201)
        ->andReturnNull(); // Não encontra o destinatário

    $this->expectException(TransferRecipientNotFoundException::class);

    $this->service->execute($transferDto);
});

test('deve lançar exceção TransferInsufficientBalanceException quando o saldo for insuficiente', function () {
      $transferDto = new TransferDto(
            amount: 5000,
            receiverAccountId: 200
        );

    $this->sender->shouldReceive('getAttribute')
        ->with('balance')
        ->andReturn(30); // Saldo insuficiente

    $this->userRepository
        ->shouldReceive('getAuthUser')
        ->once()
        ->andReturn($this->sender);

    $this->userRepository
        ->shouldReceive('findUserByAccountId')
        ->once()
        ->with(200)
        ->andReturn($this->receiver);

    $this->expectException(TransferInsufficientBalanceException::class);

    $this->service->execute($transferDto);
});

test('deve marcar a transação como falha e relançar exceção se a transferência falhar', function () {
      $transferDto = new TransferDto(
            amount: 50,
            receiverAccountId: 200
        );

    $this->userRepository
        ->shouldReceive('getAuthUser')
        ->once()
        ->andReturn($this->sender);

    $this->userRepository
        ->shouldReceive('findUserByAccountId')
        ->once()
        ->with(200)
        ->andReturn($this->receiver);

    $this->transactionRepository
        ->shouldReceive('createNewTransaction')
        ->once()
        ->andReturnTrue();

    $this->userRepository
        ->shouldReceive('transferAmount')
        ->once()
        ->with($this->sender, $this->receiver, $transferDto->amount)
        ->andThrow(new Exception("Erro ao transferir"));

    $this->transactionRepository
        ->shouldReceive('saveAsFailed')
        ->once();

    $this->transactionRepository
        ->shouldNotReceive('saveAsCompleted');

    $this->expectException(Exception::class);
    $this->expectExceptionMessage("Erro ao processar transferencia");

    $this->service->execute($transferDto);
});

