<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface SettingRepositoryContract extends RepositoryContract
{
    public function value(string $key): mixed;

    public function allKeyed(): Collection;

    public function updateValue(string $key, mixed $value, ?int $userId): void;
}
