<?php

namespace App\Repositories;

use App\Models\Tip;
use App\Repositories\Contracts\TipRepositoryContract;

class TipRepository extends EloquentRepository implements TipRepositoryContract
{
    protected function modelClass(): string
    {
        return Tip::class;
    }
}