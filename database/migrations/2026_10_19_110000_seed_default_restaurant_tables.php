<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            ['code' => 'B1', 'floor' => 1, 'capacity' => 2, 'position' => 1],
            ['code' => 'B2', 'floor' => 1, 'capacity' => 2, 'position' => 2],
            ['code' => 'B3', 'floor' => 1, 'capacity' => 4, 'position' => 3],
            ['code' => 'B4', 'floor' => 1, 'capacity' => 6, 'position' => 4],
            ['code' => 'B5', 'floor' => 1, 'capacity' => 2, 'position' => 5],
            ['code' => 'B6', 'floor' => 1, 'capacity' => 2, 'position' => 6],
            ['code' => 'B7', 'floor' => 2, 'capacity' => 2, 'position' => 1],
            ['code' => 'B8', 'floor' => 2, 'capacity' => 2, 'position' => 2],
            ['code' => 'B9', 'floor' => 2, 'capacity' => 4, 'position' => 3],
            ['code' => 'B10', 'floor' => 2, 'capacity' => 6, 'position' => 4],
            ['code' => 'B11', 'floor' => 2, 'capacity' => 2, 'position' => 5],
            ['code' => 'B12', 'floor' => 2, 'capacity' => 2, 'position' => 6],
        ];

        foreach (DB::table('restaurants')->pluck('id') as $restaurantId) {
            foreach ($tables as $table) {
                DB::table('restaurant_tables')->updateOrInsert(
                    ['restaurant_id' => $restaurantId, 'code' => $table['code']],
                    [...$table, 'restaurant_id' => $restaurantId, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
                );
            }
        }
    }

    public function down(): void
    {
        // Keep table reservations intact when rolling back the seed migration.
    }
};
