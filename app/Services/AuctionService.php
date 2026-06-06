<?php

namespace App\Services;

use App\Models\Bid;
use App\Models\Listing;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class AuctionService
{
    public function __construct(
        private EscrowService $escrowService,
    ) {}

    // ── Place a bid ───────────────────────────────────────────────────────────
    public function placeBid(Listing $listing, User $bidder, float $amount): Bid
    {
        return DB::transaction(function () use ($listing, $bidder, $amount) {

            $listing = Listing::lockForUpdate()->find($listing->id);

            $this->validateBid($listing, $bidder, $amount);

            // Mark previous bids as outbid
            if ($listing->highest_bidder_id) {
                Bid::where('listing_id', $listing->id)
                    ->where('status', 'active')
                    ->update(['status' => 'outbid']);
            }

            $bid = Bid::create([
                'listing_id' => $listing->id,
                'user_id'    => $bidder->id,
                'amount'     => $amount,
                'status'     => 'active',
            ]);

            $listing->update([
                'current_bid'       => $amount,
                'highest_bidder_id' => $bidder->id,
            ]);

            return $bid;
        });
    }

    // ── End auction and create transaction for winner ─────────────────────────
    public function endAuction(Listing $listing): void
    {
        DB::transaction(function () use ($listing) {

            $listing = Listing::lockForUpdate()->find($listing->id);

            if ($listing->status !== 'active' || !$listing->isAuction()) {
                return;
            }

            // No bids — mark inactive
            if (!$listing->highest_bidder_id) {
                $listing->update(['status' => 'inactive']);
                return;
            }

            $winningBid = Bid::where('listing_id', $listing->id)
                ->where('status', 'active')
                ->first();

            if (!$winningBid) {
                $listing->update(['status' => 'inactive']);
                return;
            }

            $winner = User::find($winningBid->user_id);
            $fee    = round($winningBid->amount * 0.05, 2);
            $payout = round($winningBid->amount - $fee, 2);

            // Create transaction for winner to pay via card
            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateCode(),
                'listing_id'       => $listing->id,
                'buyer_id'         => $winner->id,
                'seller_id'        => $listing->user_id,
                'amount'           => $winningBid->amount,
                'platform_fee'     => $fee,
                'seller_payout'    => $payout,
                'payment_method'   => 'card',
                'status'           => 'pending',
            ]);

            $winningBid->update(['status' => 'won']);

            Bid::where('listing_id', $listing->id)
                ->where('id', '!=', $winningBid->id)
                ->update(['status' => 'lost']);

            $listing->update(['status' => 'reserved']);
        });
    }

    // ── End all expired auctions ──────────────────────────────────────────────
    public function endExpiredAuctions(): int
    {
        $expired = Listing::where('type', 'auction')
            ->where('status', 'active')
            ->where('auction_ends_at', '<=', now())
            ->get();

        foreach ($expired as $listing) {
            $this->endAuction($listing);
        }

        return $expired->count();
    }

    // ── Validate bid rules ────────────────────────────────────────────────────
    private function validateBid(Listing $listing, User $bidder, float $amount): void
    {
        if (!$listing->isAuction()) {
            throw new Exception('This listing is not an auction.');
        }

        if ($listing->status !== 'active') {
            throw new Exception('This auction is not active.');
        }

        if ($listing->hasEnded()) {
            throw new Exception('This auction has already ended.');
        }

        if ($listing->user_id === $bidder->id) {
            throw new Exception('You cannot bid on your own listing.');
        }

        $minimum = $listing->minimumNextBid();
        if ($amount < $minimum) {
            throw new Exception(
                'Minimum bid is $' . number_format($minimum, 2) . '.'
            );
        }

        // — winner pays by card after auction ends
    }
}