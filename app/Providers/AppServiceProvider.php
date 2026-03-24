<?php

namespace App\Providers;

use App\Models\Listing;
use App\Policies\ListingPolicy;
use App\Services\AuctionService;
use App\Services\EscrowService;
use App\Services\WalletService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WalletService::class);

        $this->app->singleton(EscrowService::class, function ($app) {
            return new EscrowService($app->make(WalletService::class));
        });

        $this->app->singleton(AuctionService::class, function ($app) {
            return new AuctionService(
                $app->make(WalletService::class),
                $app->make(EscrowService::class),
            );
        });
    }

    public function boot(): void
    {
        Gate::policy(Listing::class, ListingPolicy::class);
        Paginator::useTailwind();
    }
}
