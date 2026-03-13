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
        Schema::create('escrow_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->decimal('held_amount', 10, 2);
            $table->enum('status', ['held', 'released', 'refunded'])->default('held');
            $table->string('released_by')->nullable();
            $table->timestamp('held_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escrow_logs');
    }
};
