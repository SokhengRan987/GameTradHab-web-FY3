<?php

namespace App\Console\Commands;

use App\Services\AuctionService;
use Illuminate\Console\Command;

class EndExpiredAuctions extends Command
{
    protected $signature   = 'auctions:end-expired';
    protected $description = 'End all auctions that have passed their end time';

    public function handle(AuctionService $auctionService): void
    {
        $this->info('Checking for expired auctions...');

        $count = $auctionService->endExpiredAuctions();

        if ($count === 0) {
            $this->info('No expired auctions found.');
        } else {
            $this->info("Ended {$count} auction(s) successfully.");
        }
    }
}
