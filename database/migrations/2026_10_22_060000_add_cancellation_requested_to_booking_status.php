<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "bookings" DROP CONSTRAINT IF EXISTS "bookings_status_check"');
            DB::statement("ALTER TABLE \"bookings\" ADD CONSTRAINT \"bookings_status_check\" CHECK (\"status\" IN ('pending', 'confirmed', 'cancellation_requested', 'cancelled', 'completed'))");

            return;
        }

        Schema::table('bookings', function (Blueprint $table): void {
            $table->enum('status', [
                'pending',
                'confirmed',
                'cancellation_requested',
                'cancelled',
                'completed',
            ])->default('pending')->change();
        });
    }

    public function down(): void
    {
        DB::table('bookings')
            ->where('status', 'cancellation_requested')
            ->update(['status' => 'confirmed']);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "bookings" DROP CONSTRAINT IF EXISTS "bookings_status_check"');
            DB::statement("ALTER TABLE \"bookings\" ADD CONSTRAINT \"bookings_status_check\" CHECK (\"status\" IN ('pending', 'confirmed', 'cancelled', 'completed'))");

            return;
        }

        Schema::table('bookings', function (Blueprint $table): void {
            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
            ])->default('pending')->change();
        });
    }
};
