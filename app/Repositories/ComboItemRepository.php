<?php

namespace App\Repositories;

use App\Models\ComboItem;
use App\Repositories\Contracts\ComboItemRepositoryContract;

class ComboItemRepository extends EloquentRepository implements ComboItemRepositoryContract
{
    protected function modelClass(): string
    {
        return ComboItem::class;
    }
}