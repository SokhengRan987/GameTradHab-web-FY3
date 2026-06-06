<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop constraint if it exists
        DB::statement("
            DO $$ BEGIN
                ALTER TABLE wallet_logs DROP CONSTRAINT IF EXISTS wallet_logs_type_check;
            EXCEPTION WHEN others THEN NULL;
            END $$;
        ");

        DB::statement("
            ALTER TABLE wallet_logs ADD CONSTRAINT wallet_logs_type_check
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
        DB::statement("ALTER TABLE wallet_logs DROP CONSTRAINT IF EXISTS wallet_logs_type_check");
    }
};
