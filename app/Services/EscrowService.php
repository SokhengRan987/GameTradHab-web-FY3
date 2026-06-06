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

    // ── Called when buyer clicks "Buy Now" ───────────────────────────────────
    // Creates escrow log and holds funds
    public function hold(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {

            EscrowLog::create([
                'transaction_id' => $transaction->id,
                'held_amount'    => $transaction->amount,
                'status'         => 'held',
                'held_at'        => now(),
            ]);

            $transaction->update([
                'status'          => 'escrow',
                'review_deadline' => now()->addHours(48),
            ]);

            $transaction->listing->update(['status' => 'sold']);
        });
    }

    // ── Called when buyer clicks "Confirm Receipt" ───────────────────────────
    // Pays seller and marks transaction completed
    public function release(Transaction $transaction, string $releasedBy = 'buyer'): void
    {
        DB::transaction(function () use ($transaction, $releasedBy) {

            // Pay the seller
            $this->wallet->credit(
                userId:      $transaction->seller_id,
                amount:      $transaction->seller_payout,
                type:        'payout',
                reference:   $transaction->transaction_code,
                description: 'Sale payout: ' . $transaction->listing->title
            );

            // Update escrow log only if it exists
            $escrowLog = $transaction->escrowLog;
            if ($escrowLog) {
                $escrowLog->update([
                    'status'      => 'released',
                    'released_by' => $releasedBy,
                    'released_at' => now(),
                ]);
            }

            // Mark transaction as completed
            $transaction->update([
                'status'             => 'completed',
                'buyer_confirmed_at' => now(),
                'escrow_released_at' => now(),
            ]);

            // Mark listing as sold
            $transaction->listing->update(['status' => 'sold']);

            // Increment seller total sales count
            $transaction->seller->increment('total_sales');
        });
    }

    // ── Called by admin when dispute is resolved ─────────────────────────────
    // Refunds buyer and reopens listing
    public function refund(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {

            $escrowLog = $transaction->escrowLog;
            if ($escrowLog) {
                $escrowLog->update([
                    'status'      => 'refunded',
                    'released_by' => 'admin',
                    'released_at' => now(),
                ]);
            }

            $transaction->update(['status' => 'refunded']);
            $transaction->listing->update(['status' => 'active']);
        });
    }
}