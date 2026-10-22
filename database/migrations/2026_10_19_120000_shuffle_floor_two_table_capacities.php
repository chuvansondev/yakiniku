<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Keep the same total capacity while changing the larger tables' positions on floor 2.
        $capacities = [
            'B7' => 2,
            'B8' => 4,
            'B9' => 2,
            'B10' => 2,
            'B11' => 6,
            'B12' => 2,
        ];

        foreach (DB::table('restaurants')->pluck('id') as $restaurantId) {
            foreach ($capacities as $code => $capacity) {
                DB::table('restaurant_tables')
                    ->where('restaurant_id', $restaurantId)
                    ->where('floor', 2)
                    ->where('code', $code)
                    ->update(['capacity' => $capacity, 'updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        $capacities = [
            'B7' => 2,
            'B8' => 2,
            'B9' => 4,
            'B10' => 6,
            'B11' => 2,
            'B12' => 2,
        ];

        foreach (DB::table('restaurants')->pluck('id') as $restaurantId) {
            foreach ($capacities as $code => $capacity) {
                DB::table('restaurant_tables')
                    ->where('restaurant_id', $restaurantId)
                    ->where('floor', 2)
                    ->where('code', $code)
                    ->update(['capacity' => $capacity, 'updated_at' => now()]);
            }
        }
    }
};
