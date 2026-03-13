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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', ['MOBA', 'FPS', 'RPG', 'Battle Royale', 'Sports', 'Other']);
            $table->enum('platform', ['Mobile', 'PC', 'Console', 'All']);
            $table->string('banner_image')->nullable();
            $table->string('icon_image')->nullable();
            $table->json('rank_options')->nullable();
            $table->json('server_options')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
