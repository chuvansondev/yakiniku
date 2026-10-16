<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kid_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('food');
            $table->string('food_category')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->foreignId('delete_at')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('delete_by')->nullable()->constrained('users')->nullOnDelete();
        });

        if (Schema::hasColumn('menu_items', 'is_for_kids')) {
            DB::table('menu_items')
                ->where('is_for_kids', true)
                ->orderBy('id')
                ->get()
                ->each(function (object $menuItem): void {
                    $slug = $menuItem->slug ?: Str::slug($menuItem->name);
                    $baseSlug = $slug;
                    $suffix = 1;

                    while (DB::table('kid_items')->where('slug', $slug)->exists()) {
                        $slug = Str::slug($baseSlug.'-menu-'.$menuItem->id.'-'.$suffix);
                        $suffix++;
                    }

                    DB::table('kid_items')->insert([
                        'name' => $menuItem->name,
                        'slug' => $slug,
                        'type' => 'food',
                        'food_category' => null,
                        'description' => $menuItem->description,
                        'image' => $menuItem->image,
                        'price' => $menuItem->price,
                        'sort_order' => $menuItem->sort_order,
                        'status' => $menuItem->status,
                        'created_at' => $menuItem->created_at,
                        'updated_at' => $menuItem->updated_at,
                    ]);
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kid_items');
    }
};
