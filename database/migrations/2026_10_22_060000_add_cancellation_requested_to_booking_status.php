<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
