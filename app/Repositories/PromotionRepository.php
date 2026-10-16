<?php

namespace App\Repositories;

use App\Models\Promotion;
use App\Repositories\Contracts\PromotionRepositoryContract;

class PromotionRepository extends EloquentRepository implements PromotionRepositoryContract
{
    protected function modelClass(): string
    {
        return Promotion::class;
    }
}