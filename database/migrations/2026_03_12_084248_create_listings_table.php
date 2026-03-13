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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('rank')->nullable();
            $table->integer('level')->nullable();
            $table->string('server')->nullable();
            $table->enum('platform', ['Mobile', 'PC', 'Console']);
            $table->string('account_age')->nullable();
            $table->json('heroes_skins')->nullable();
            $table->json('tags')->nullable();
            $table->enum('type', ['fixed', 'auction'])->default('fixed');
            $table->enum('status', [
                'pending', 'active', 'sold', 'rejected', 'inactive'
            ])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
