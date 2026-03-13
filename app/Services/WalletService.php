<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletLog;
use Exception;
use Illuminate\Support\Facades\DB;

class WalletService
{
    // Add money to a user's wallet
    public function credit(
        int $userId,
        float $amount,
        string $type,
        ?string $reference = null,
        ?string $description = null
    ): WalletLog {
        return DB::transaction(function () use ($userId, $amount, $type, $reference, $description) {

            // Lock the user row so two requests can't credit at the same time
            $user = User::lockForUpdate()->findOrFail($userId);

            $user->increment('wallet_balance', $amount);

            return WalletLog::create([
                'user_id'       => $userId,
                'type'          => $type,
                'amount'        => $amount,
                'balance_after' => $user->fresh()->wallet_balance,
                'reference'     => $reference,
                'description'   => $description,
            ]);
        });
    }

    // Remove money from a user's wallet
    public function debit(
        int $userId,
        float $amount,
        string $type,
        ?string $reference = null,
        ?string $description = null
    ): WalletLog {
        return DB::transaction(function () use ($userId, $amount, $type, $reference, $description) {

            // Lock the user row to prevent race conditions
            $user = User::lockForUpdate()->findOrFail($userId);

            // Stop if not enough balance
            if ($user->wallet_balance < $amount) {
                throw new Exception('Insufficient wallet balance.');
            }

            $user->decrement('wallet_balance', $amount);

            return WalletLog::create([
                'user_id'       => $userId,
                'type'          => $type,
                'amount'        => $amount,
                'balance_after' => $user->fresh()->wallet_balance,
                'reference'     => $reference,
                'description'   => $description,
            ]);
        });
    }

    // Check if a user has enough balance before buying
    public function hasSufficientBalance(int $userId, float $amount): bool
    {
        $user = User::findOrFail($userId);
        return $user->wallet_balance >= $amount;
    }
}