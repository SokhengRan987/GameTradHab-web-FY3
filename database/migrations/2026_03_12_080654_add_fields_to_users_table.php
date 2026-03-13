<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer')->after('avatar');
            $table->decimal('wallet_balance', 12, 2)->default(0)->after('role');
            $table->decimal('rating_avg', 3, 2)->default(0)->after('wallet_balance');
            $table->integer('total_sales')->default(0)->after('rating_avg');
            $table->boolean('is_verified')->default(false)->after('total_sales');
            $table->boolean('is_banned')->default(false)->after('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'phone', 'avatar', 'role',
                'wallet_balance', 'rating_avg', 'total_sales',
                'is_verified', 'is_banned',
            ]);
        });
    }
};
