<?php

namespace App\Repositories;

use App\Models\MenuCategory;
use App\Repositories\Contracts\MenuCategoryRepositoryContract;

class MenuCategoryRepository extends EloquentRepository implements MenuCategoryRepositoryContract
{
    protected function modelClass(): string
    {
        return MenuCategory::class;
    }
}