<?php

namespace App\Services;

use App\Models\EscrowLog;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class EscrowService
{
    public function __construct(
        private WalletService $wallet
    ) {}

    // ── Called when buyer clicks "Buy Now" ───────────
    // Deducts from buyer wallet and holds in escrow
    public function hold(Transaction $transaction): EscrowLog
    {
        return DB::transaction(function () use ($transaction) {

            // Deduct from buyer wallet
            $this->wallet->debit(
                userId:      $transaction->buyer_id,
                amount:      $transaction->amount,
                type:        'purchase',
                reference:   $transaction->transaction_code,
                description: 'Purchase: ' . $transaction->listing->title
            );

            // Create the escrow record
            $escrowLog = EscrowLog::create([
                'transaction_id' => $transaction->id,
                'held_amount'    => $transaction->amount,
                'status'         => 'held',
                'held_at'        => now(),
            ]);

            // Update transaction status to escrow
            // Give buyer 48 hours to confirm the account
            $transaction->update([
                'status'          => 'escrow',
                'review_deadline' => now()->addHours(48),
            ]);

            return $escrowLog;
        });
    }

    // ── Called when buyer clicks "Confirm Receipt" ───
    // Releases escrow and pays the seller
    public function release(Transaction $transaction, string $releasedBy = 'buyer'): void
    {
        DB::transaction(function () use ($transaction, $releasedBy) {

            // Pay the seller (price minus platform fee)
            $this->wallet->credit(
                userId:      $transaction->seller_id,
                amount:      $transaction->seller_payout,
                type:        'payout',
                reference:   $transaction->transaction_code,
                description: 'Sale payout: ' . $transaction->listing->title
            );

            // Mark escrow as released
            $transaction->escrowLog->update([
                'status'      => 'released',
                'released_by' => $releasedBy,
                'released_at' => now(),
            ]);

            // Mark transaction as completed
            $transaction->update([
                'status'              => 'completed',
                'buyer_confirmed_at'  => now(),
                'escrow_released_at'  => now(),
            ]);

            // Mark the listing as sold
            $transaction->listing->update(['status' => 'sold']);

            // Increment seller total sales count
            $transaction->seller->increment('total_sales');
        });
    }

    // ── Called by admin when dispute is resolved ─────
    // Refunds the buyer and reopens the listing
    public function refund(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {

            // Refund full amount back to buyer
            $this->wallet->credit(
                userId:      $transaction->buyer_id,
                amount:      $transaction->amount,
                type:        'refund',
                reference:   $transaction->transaction_code,
                description: 'Refund: ' . $transaction->listing->title
            );

            // Mark escrow as refunded
            $transaction->escrowLog->update([
                'status'      => 'refunded',
                'released_by' => 'admin',
                'released_at' => now(),
            ]);

            // Mark transaction as refunded
            $transaction->update(['status' => 'refunded']);

            // Make the listing active again so it can be sold
            $transaction->listing->update(['status' => 'active']);
        });
    }
}