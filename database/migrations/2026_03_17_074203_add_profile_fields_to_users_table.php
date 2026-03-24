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
            //
            $table->string('full_name')->nullable()->after('name');
            $table->string('country')->nullable()->after('full_name');
            $table->date('date_of_birth')->nullable()->after('country');
            $table->string('phone_country_code')->nullable()->after('date_of_birth');
            $table->string('phone_number')->nullable()->after('phone_country_code');
            $table->string('telegram')->nullable()->after('phone_number');
            $table->string('whatsapp')->nullable()->after('telegram');
            $table->string('discord')->nullable()->after('whatsapp');
            $table->string('line_id')->nullable()->after('discord');
            $table->boolean('profile_completed')->default(false)->after('line_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn([
                'full_name',
                'country',
                'date_of_birth',
                'phone_country_code',
                'phone_number',
                'telegram',
                'whatsapp',
                'discord',
                'line_id',
                'profile_completed',
            ]);
        });
    }
};
