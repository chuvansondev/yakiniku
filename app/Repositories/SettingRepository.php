<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryContract;

class SettingRepository extends EloquentRepository implements SettingRepositoryContract
{
    protected function modelClass(): string
    {
        return Setting::class;
    }
}