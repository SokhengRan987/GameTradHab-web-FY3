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
            $table->string('contact_telegram')->nullable()->after('account_age');
            $table->string('contact_whatsapp')->nullable()->after('contact_telegram');
            $table->string('contact_discord')->nullable()->after('contact_whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['contact_telegram', 'contact_whatsapp', 'contact_discord']);
        });
    }
};
