<?php

namespace App\Repositories;

use App\Models\RestaurantTable;
use App\Repositories\Contracts\RestaurantTableRepositoryContract;

class RestaurantTableRepository extends EloquentRepository implements RestaurantTableRepositoryContract
{
    protected function modelClass(): string
    {
        return RestaurantTable::class;
    }
}