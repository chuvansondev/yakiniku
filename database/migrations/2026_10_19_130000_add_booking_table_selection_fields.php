<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            if (! Schema::hasColumn('bookings', 'floor')) {
                $table->unsignedSmallInteger('floor')->default(1);
            }

            if (! Schema::hasColumn('bookings', 'table_codes')) {
                $table->json('table_codes')->nullable();
            }

            if (! Schema::hasColumn('bookings', 'pre_order_items')) {
                $table->json('pre_order_items')->nullable();
            }

            if (! Schema::hasColumn('bookings', 'create_by')) {
                $table->foreignId('create_by')->nullable()->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('bookings', 'update_by')) {
                $table->foreignId('update_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            foreach (['update_by', 'create_by'] as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropConstrainedForeignId($column);
                }
            }

            foreach (['pre_order_items', 'table_codes', 'floor'] as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
