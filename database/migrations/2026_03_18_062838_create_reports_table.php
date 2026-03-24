<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('reporter_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->enum('reason', [
                'scam',
                'fake_screenshots',
                'wrong_info',
                'duplicate',
                'inappropriate',
                'other',
            ]);
            $table->text('details')->nullable();
            $table->enum('status', [
                'pending',
                'resolved',
                'dismissed',
            ])->default('pending');
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->text('admin_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
