<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryContract;
use Illuminate\Support\Collection;

class SettingRepository extends EloquentRepository implements SettingRepositoryContract
{
    protected function modelClass(): string
    {
        return Setting::class;
    }

    public function value(string $key): mixed
    {
        return Setting::query()->where('key', $key)->value('value');
    }

    public function allKeyed(): Collection
    {
        return Setting::query()->pluck('value', 'key');
    }

    public function updateValue(string $key, mixed $value, ?int $userId): void
    {
        Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
