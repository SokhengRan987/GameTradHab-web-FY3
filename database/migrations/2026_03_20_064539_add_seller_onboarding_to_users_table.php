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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('seller_onboarded')->default(false)->after('profile_completed');
            $table->string('seller_games')->nullable()->after('seller_onboarded');
            $table->enum('seller_stock_source', ['self_farmed', 'resell'])->nullable()->after('seller_games');
            $table->boolean('seller_sells_elsewhere')->nullable()->after('seller_stock_source');
            $table->string('seller_other_platforms')->nullable()->after('seller_sells_elsewhere');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'seller_onboarded',
                'seller_games',
                'seller_stock_source',
                'seller_sells_elsewhere',
                'seller_other_platforms',
            ]);
        }); 
    }
};
