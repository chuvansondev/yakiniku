<?php

namespace App\Repositories;

use App\Models\KidItem;
use App\Repositories\Contracts\KidItemRepositoryContract;

class KidItemRepository extends EloquentRepository implements KidItemRepositoryContract
{
    protected function modelClass(): string
    {
        return KidItem::class;
    }
}