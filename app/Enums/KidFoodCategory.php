<?php

namespace App\Enums;

enum KidFoodCategory: string
{
    case Meat = 'meat';
    case SideDish = 'side_dish';
    case Vegetable = 'vegetable';
    case Soup = 'soup';
    case RiceNoodles = 'rice_noodles';
    case Dessert = 'dessert';
}
