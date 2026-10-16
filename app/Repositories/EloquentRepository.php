<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class EloquentRepository implements RepositoryContract
{
    /** @return class-string<Model> */
    abstract protected function modelClass(): string;

    /** @return Model */
    protected function newModel(): Model
    {
        $modelClass = $this->modelClass();

        return new $modelClass;
    }

    /** @return Collection<int, Model> */
    public function all(): Collection
    {
        return $this->newModel()->newQuery()->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->newModel()->newQuery()->paginate($perPage);
    }

    public function find(int|string $id): ?Model
    {
        return $this->newModel()->newQuery()->find($id);
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->newModel()->newQuery()->findOrFail($id);
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): Model
    {
        return $this->newModel()->newQuery()->create($attributes);
    }

    /** @param array<string, mixed> $attributes */
    public function update(int|string|Model $record, array $attributes): Model
    {
        $record = $record instanceof Model ? $record : $this->findOrFail($record);
        $record->fill($attributes)->save();

        return $record;
    }

    public function delete(int|string|Model $record): bool
    {
        $record = $record instanceof Model ? $record : $this->findOrFail($record);

        return (bool) $record->delete();
    }
}
