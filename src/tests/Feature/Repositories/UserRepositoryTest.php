<?php

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new UserRepository();
});

it('deve buscar usuário pelo account_id', function () {
    $user = User::factory()->create();
    
    $found = $this->repository->findUserByAccountId($user->account_id);
   
    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($user->id);
});

it('deve buscar usuário pelo id', function () {
    $user = User::factory()->create();

    $found = $this->repository->findUserById($user->id);

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($user->id);
});

it('deve creditar valor na conta do usuário', function () {
    $user = User::factory()->create(['balance' => 100]);

    $this->repository->creditUserAccount($user, 50);

    expect($user->fresh()->balance)->toBe(150);
});

it('deve debitar valor na conta do usuário', function () {
    $user = User::factory()->create(['balance' => 200]);

    $this->repository->debitUserAccount($user, 80);

    expect($user->fresh()->balance)->toBe(120);
});

it('deve transferir valor entre usuários', function () {
    $sender = User::factory()->create(['balance' => 300]);
    $receiver = User::factory()->create(['balance' => 100]);

    $this->repository->transferAmount($sender, $receiver, 75);

    expect($sender->fresh()->balance)->toBe(225)
        ->and($receiver->fresh()->balance)->toBe(175);
});
