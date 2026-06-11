<?php

namespace App\Console\Commands;

use App\Models\Listing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CloseAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

    $endedAuctions = Listing::where('type', 'auction')
        ->where('status', 'active')
        ->where('auction_ends_at', '<=', now())
        ->get();

    foreach ($endedAuctions as $listing) {

        DB::transaction(function () use ($listing) {

            // ✅ Mark auction as ended
            $listing->update([
                'status' => 'inactive',
            ]);

            // ✅ Get winner
            $winner = $listing->highestBidder;

            if ($winner) {
                // ✅ Create payment record
                \App\Models\Transaction::create([
                    'buyer_id'   => $winner->id,
                    'seller_id'  => $listing->user_id,
                    'listing_id' => $listing->id,
                    'amount'     => $listing->current_bid,
                    'status'     => 'pending', // waiting for payment
                ]);

                $this->info("Auction #{$listing->id} ended. Winner: {$winner->name} (Payment created)");

            } else {
                $this->info("Auction #{$listing->id} ended with no bids.");
            }
        });

    }

    return Command::SUCCESS;
    }
}
