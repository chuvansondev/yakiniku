<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bookings', 'completed_at')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->timestamp('completed_at')->nullable()->index();
            });
        }

        DB::table('bookings')
            ->where('status', 'completed')
            ->whereNull('completed_at')
            ->update(['completed_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'completed_at')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->dropIndex(['completed_at']);
                $table->dropColumn('completed_at');
            });
        }
    }
};
