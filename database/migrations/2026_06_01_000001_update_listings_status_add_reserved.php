<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE listings DROP CONSTRAINT IF EXISTS listings_status_check');
            DB::statement("ALTER TABLE listings ADD CONSTRAINT listings_status_check CHECK (status IN ('pending','active','sold','rejected','inactive','reserved'))");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE listings MODIFY COLUMN status ENUM('pending','active','sold','rejected','inactive','reserved') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('listings', function (Blueprint $table) {
                $table->enum('status', ['pending', 'active', 'sold', 'rejected', 'inactive', 'reserved'])
                    ->default('pending')
                    ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE listings DROP CONSTRAINT IF EXISTS listings_status_check');
            DB::statement("ALTER TABLE listings ADD CONSTRAINT listings_status_check CHECK (status IN ('pending','active','sold','rejected','inactive'))");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE listings MODIFY COLUMN status ENUM('pending','active','sold','rejected','inactive') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('listings', function (Blueprint $table) {
                $table->enum('status', ['pending', 'active', 'sold', 'rejected', 'inactive'])
                    ->default('pending')
                    ->change();
            });
        }
    }
};
