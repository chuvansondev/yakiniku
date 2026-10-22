<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table): void {
            $table->foreignId('create_by')->nullable()->after('description')->constrained('users')->nullOnDelete();
            $table->foreignId('update_by')->nullable()->after('create_by')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('update_by');
            $table->dropConstrainedForeignId('create_by');
        });
    }
};
