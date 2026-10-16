<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'is_locked')) {
                $table->boolean('is_locked')->default(false);
            }

            if (! Schema::hasColumn('users', 'session_version')) {
                $table->unsignedInteger('session_version')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'session_version')) {
                $table->dropColumn('session_version');
            }

            if (Schema::hasColumn('users', 'is_locked')) {
                $table->dropColumn('is_locked');
            }
        });
    }
};
