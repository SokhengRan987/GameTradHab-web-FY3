<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            if (!Schema::hasColumn('listings', 'is_flagged')) {
                $table->boolean('is_flagged')->default(false)->after('is_featured');
            }
            if (!Schema::hasColumn('listings', 'flag_reason')) {
                $table->string('flag_reason')->nullable()->after('is_flagged');
            }
            if (!Schema::hasColumn('listings', 'flagged_at')) {
                $table->timestamp('flagged_at')->nullable()->after('flag_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['is_flagged', 'flag_reason', 'flagged_at']);
        });
    }
};
