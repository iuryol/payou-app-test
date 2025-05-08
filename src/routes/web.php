<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReversalController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth','verified'])->group(function(){
    Route::get('/',[HomeController::class,'index'])->name('home.index');
    Route::get('/deposit',[DepositController::class,'index'])->name('deposit.index');
    Route::post('/deposit',[DepositController::class,'store'])->name('deposit.store');
    Route::get('/transfer',[TransferController::class,'index'])->name('transfer.index');
    Route::post('/transfer',[TransferController::class,'store'])->name('transfer.store');
    Route::get('/reversal',[ReversalController::class,'index'])->name('reversal.index');
    Route::post('/reversal/{transaction}',[ReversalController::class,'store'])->name('reversal.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
