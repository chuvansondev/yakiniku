<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_status_histories', function (Blueprint $table): void {
            $table->string('from_status', 32)->nullable()->change();
            $table->string('to_status', 32)->change();
        });
    }

    public function down(): void
    {
        Schema::table('booking_status_histories', function (Blueprint $table): void {
            $table->string('from_status', 20)->nullable()->change();
            $table->string('to_status', 20)->change();
        });
    }
};
