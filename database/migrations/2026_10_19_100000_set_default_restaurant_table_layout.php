<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $layout = [
            'B1' => ['floor' => 1, 'capacity' => 2, 'position' => 1],
            'B2' => ['floor' => 1, 'capacity' => 2, 'position' => 2],
            'B3' => ['floor' => 1, 'capacity' => 4, 'position' => 3],
            'B4' => ['floor' => 1, 'capacity' => 6, 'position' => 4],
            'B5' => ['floor' => 1, 'capacity' => 2, 'position' => 5],
            'B6' => ['floor' => 1, 'capacity' => 2, 'position' => 6],
            'B7' => ['floor' => 2, 'capacity' => 4, 'position' => 1],
            'B8' => ['floor' => 2, 'capacity' => 2, 'position' => 2],
            'B9' => ['floor' => 2, 'capacity' => 6, 'position' => 3],
            'B10' => ['floor' => 2, 'capacity' => 2, 'position' => 4],
            'B11' => ['floor' => 2, 'capacity' => 2, 'position' => 5],
            'B12' => ['floor' => 2, 'capacity' => 2, 'position' => 6],
        ];

        foreach ($layout as $code => $attributes) {
            DB::table('restaurant_tables')->where('code', $code)->update($attributes);
        }
    }

    public function down(): void
    {
        // Preserve current table setup when rolling back this data adjustment.
    }
};
