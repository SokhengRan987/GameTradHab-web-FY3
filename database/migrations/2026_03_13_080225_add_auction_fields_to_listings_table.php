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
            // Starting price for auction
            $table->decimal('starting_price', 10, 2)->nullable()->after('price');

            // Minimum amount each bid must increase by
            $table->decimal('bid_increment', 10, 2)->default(1.00)->after('starting_price');

            // When the auction ends
            $table->timestamp('auction_ends_at')->nullable()->after('bid_increment');

            // Track current highest bid amount
            $table->decimal('current_bid', 10, 2)->nullable()->after('auction_ends_at');

            // Track who is currently winning
            $table->foreignId('highest_bidder_id')
                ->nullable()
                ->after('current_bid')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['highest_bidder_id']);
            $table->dropColumn([
                'starting_price',
                'bid_increment',
                'auction_ends_at',
                'current_bid',
                'highest_bidder_id',
            ]);
        });
    }
};
