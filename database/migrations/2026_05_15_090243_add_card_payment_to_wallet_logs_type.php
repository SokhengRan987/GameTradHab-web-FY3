<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE wallet_logs DROP CHECK wallet_logs_type_check");
        } catch (\Exception $e) {
            // Ignore if constraint doesn't exist
        }

        DB::statement("
            ALTER TABLE wallet_logs
            ADD CONSTRAINT wallet_logs_type_check
            CHECK (type IN (
                'topup',
                'purchase',
                'payout',
                'refund',
                'withdrawal',
                'card_payment'
            ))
        ");
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE wallet_logs DROP CHECK wallet_logs_type_check");
        } catch (\Exception $e) {
            // Ignore if constraint doesn't exist
        }

        DB::statement("
            ALTER TABLE wallet_logs
            ADD CONSTRAINT wallet_logs_type_check
            CHECK (type IN (
                'topup',
                'purchase',
                'payout',
                'refund',
                'withdrawal'
            ))
        ");
    }
};
