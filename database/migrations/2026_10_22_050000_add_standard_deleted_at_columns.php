<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'banners', 'combos', 'kid_items', 'menu_categories', 'menu_items',
        'promotions', 'recipes', 'restaurants', 'tips',
    ];

    public function up(): void
    {
        foreach ($this->tables as $name) {
            if (! Schema::hasColumn($name, 'deleted_at')) {
                Schema::table($name, function (Blueprint $table): void {
                    $table->softDeletes();
                });
            }

            if (Schema::hasColumn($name, 'delete_by')) {
                DB::table($name)
                    ->whereNotNull('delete_by')
                    ->whereNull('deleted_at')
                    ->update(['deleted_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $name) {
            if (Schema::hasColumn($name, 'deleted_at')) {
                Schema::table($name, function (Blueprint $table): void {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
