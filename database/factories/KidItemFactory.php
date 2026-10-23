<?php

namespace Database\Factories;

use App\Enums\KidFoodCategory;
use App\Enums\KidItemType;
use App\Models\KidItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<KidItem> */
class KidItemFactory extends Factory
{
    protected $model = KidItem::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'type' => KidItemType::Food->value,
            'food_category' => KidFoodCategory::Meat->value,
            'price' => fake()->numberBetween(20000, 150000),
            'sort_order' => 0,
            'status' => true,
        ];
    }
}
