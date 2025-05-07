<?php

namespace App\Providers;


use App\Interfaces\DepositServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\TransferServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\DepositService;
use App\Services\TransferService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            DepositServiceInterface::class,
            DepositService::class
        );

        $this->app->bind(
            TransferServiceInterface::class,
            TransferService::class
        );
     
        $this->app->bind(
            TransactionRepositoryInterface::class,
            TransactionRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
