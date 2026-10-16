<?php

namespace App\Repositories;

use App\Models\Restaurant;
use App\Repositories\Contracts\RestaurantRepositoryContract;

class RestaurantRepository extends EloquentRepository implements RestaurantRepositoryContract
{
    protected function modelClass(): string
    {
        return Restaurant::class;
    }
}