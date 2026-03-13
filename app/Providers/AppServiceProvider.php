<?php

namespace App\Providers;
use App\Services\WalletService;
use App\Services\EscrowService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register WalletService as a singleton
        // (same instance reused across the app)
        $this->app->singleton(WalletService::class);

        // Register EscrowService — it needs WalletService injected
        $this->app->singleton(EscrowService::class, function ($app) {
            return new EscrowService($app->make(WalletService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useTailwind();
    }
}
