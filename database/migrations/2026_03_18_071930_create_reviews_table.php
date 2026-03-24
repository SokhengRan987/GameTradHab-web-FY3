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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Which transaction this review is for
            $table->foreignId('transaction_id')
                ->unique() // one review per transaction
                ->constrained()
                ->onDelete('cascade');

            // Who wrote the review (buyer)
            $table->foreignId('reviewer_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Who is being reviewed (seller)
            $table->foreignId('seller_id')
                ->constrained('users')
                ->onDelete('cascade');

            // The listing that was sold
            $table->foreignId('listing_id')
                ->constrained()
                ->onDelete('cascade');

            // Star rating 1-5
            $table->tinyInteger('rating');

            // Written comment
            $table->text('comment')->nullable();

            // Seller can reply to review
            $table->text('seller_reply')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
