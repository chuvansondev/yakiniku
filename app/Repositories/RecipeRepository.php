<?php

namespace App\Repositories;

use App\Models\Recipe;
use App\Repositories\Contracts\RecipeRepositoryContract;

class RecipeRepository extends EloquentRepository implements RecipeRepositoryContract
{
    protected function modelClass(): string
    {
        return Recipe::class;
    }
}