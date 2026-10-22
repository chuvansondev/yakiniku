<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table): void {
            $table->foreignId('create_by')->nullable()->after('description')->constrained('users')->nullOnDelete();
            $table->foreignId('update_by')->nullable()->after('create_by')->constrained('users')->nullOnDelete();
        });

        Schema::table('permission_role', function (Blueprint $table): void {
            $table->index(['role_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::table('permission_role', function (Blueprint $table): void {
            $table->dropIndex(['role_id', 'permission_id']);
        });

        Schema::table('permissions', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('update_by');
            $table->dropConstrainedForeignId('create_by');
        });
    }
};
