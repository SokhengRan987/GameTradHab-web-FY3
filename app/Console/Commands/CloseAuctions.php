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
    protected $signature = 'auctions:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close ended auctions and create transactions';

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

                // ✅ find highest bid
                $winningBid = $listing->bids()
                    ->orderByDesc('amount')
                    ->first();

                // ✅ close auction
                $listing->update([
                    'status' => 'inactive',
                ]);

                if ($winningBid) {

                    $winner = $winningBid->user;

                    // ✅ mark all bids lost
                    $listing->bids()->update(['status' => 'lost']);

                    // ✅ mark winner
                    $winningBid->update(['status' => 'won']);

                    // ✅ create transaction
                    \App\Models\Transaction::create([
                        'transaction_code' => \App\Models\Transaction::generateCode(),
                        'buyer_id'         => $winner->id,
                        'seller_id'        => $listing->user_id,
                        'listing_id'       => $listing->id,
                        'amount'           => $winningBid->amount,
                        'platform_fee'     => round($winningBid->amount * 0.05, 2),
                        'seller_payout'    => round($winningBid->amount * 0.95, 2),
                        'payment_method'   => 'card',
                        'status'           => 'pending',
                    ]);

                    $this->info("✅ Auction #{$listing->id} → Winner: {$winner->name}");

                } else {
                    $this->info("⚠️ Auction #{$listing->id} ended with no bids.");
                }

            });
        }

        return Command::SUCCESS;
    }
}
