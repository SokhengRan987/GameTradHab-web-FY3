<?php

namespace App\Providers;
use App\Services\WalletService;
use App\Services\EscrowService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\Listing;
use App\Policies\ListingPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
        Listing::class => ListingPolicy::class,
    ];

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
        // Register policy manually
        Gate::policy(Listing::class, ListingPolicy::class);

        // Use Tailwind pagination
        Paginator::useTailwind();
    }
}
