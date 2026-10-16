<?php

namespace App\Repositories;

use App\Models\Combo;
use App\Repositories\Contracts\ComboRepositoryContract;

class ComboRepository extends EloquentRepository implements ComboRepositoryContract
{
    protected function modelClass(): string
    {
        return Combo::class;
    }
}