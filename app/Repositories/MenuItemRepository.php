<?php

namespace App\Repositories;

use App\Models\MenuItem;
use App\Repositories\Contracts\MenuItemRepositoryContract;

class MenuItemRepository extends EloquentRepository implements MenuItemRepositoryContract
{
    protected function modelClass(): string
    {
        return MenuItem::class;
    }
}