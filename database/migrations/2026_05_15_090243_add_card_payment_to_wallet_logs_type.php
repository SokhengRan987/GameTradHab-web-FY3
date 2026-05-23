<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Only run for MySQL
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        try {
            DB::statement("ALTER TABLE wallet_logs DROP CHECK wallet_logs_type_check");
        } catch (\Exception $e) {
            // ignore if not exists
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
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        try {
            DB::statement("ALTER TABLE wallet_logs DROP CHECK wallet_logs_type_check");
        } catch (\Exception $e) {}

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
