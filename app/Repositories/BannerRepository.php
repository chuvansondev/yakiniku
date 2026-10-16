<?php

namespace App\Repositories;

use App\Models\Banner;
use App\Repositories\Contracts\BannerRepositoryContract;

class BannerRepository extends EloquentRepository implements BannerRepositoryContract
{
    protected function modelClass(): string
    {
        return Banner::class;
    }
}