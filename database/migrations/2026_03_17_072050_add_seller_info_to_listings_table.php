<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            // Contact fields (missing ones)
            if (!Schema::hasColumn('listings', 'contact_whatsapp')) {
                $table->string('contact_whatsapp')->nullable()->after('contact_telegram');
            }
            if (!Schema::hasColumn('listings', 'contact_discord')) {
                $table->string('contact_discord')->nullable()->after('contact_whatsapp');
            }

            // Seller info fields
            if (!Schema::hasColumn('listings', 'seller_phone')) {
                $table->string('seller_phone')->nullable()->after('contact_discord');
            }
            if (!Schema::hasColumn('listings', 'seller_country')) {
                $table->string('seller_country')->nullable()->after('seller_phone');
            }
            if (!Schema::hasColumn('listings', 'stock_source')) {
                $table->enum('stock_source', [
                    'self_farmed',
                    'resell',
                    'gifted',
                    'other',
                ])->nullable()->after('seller_country');
            }
            if (!Schema::hasColumn('listings', 'stock_source_note')) {
                $table->text('stock_source_note')->nullable()->after('stock_source');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            //
            $table->dropColumn([
                'seller_phone',
                'seller_country',
                'stock_source',
                'stock_source_note',
            ]);
        });
    }
};
