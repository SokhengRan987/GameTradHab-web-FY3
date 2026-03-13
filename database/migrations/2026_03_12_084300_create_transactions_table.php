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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('listing_id')->constrained()->onDelete('restrict');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('seller_id')->constrained('users')->onDelete('restrict');
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('seller_payout', 10, 2)->default(0);
            $table->enum('status', [
                'pending', 'escrow', 'completed', 'disputed', 'refunded', 'cancelled'
            ])->default('pending');
            $table->string('payment_method')->default('wallet');
            $table->timestamp('review_deadline')->nullable();
            $table->timestamp('buyer_confirmed_at')->nullable();
            $table->timestamp('escrow_released_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
