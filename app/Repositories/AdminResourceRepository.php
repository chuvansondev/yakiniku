<?php

namespace App\Repositories;

use App\Contracts\Repositories\AdminResourceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AdminResourceRepository implements AdminResourceRepositoryInterface
{
    public function all(string $modelClass, array $with = [], array $orderBy = [], array $filters = [], ?int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->query($modelClass, $with, $filters, $orderBy);

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function paginate(string $modelClass, array $criteria, array $options, array $with = [], array $defaultOrderBy = []): LengthAwarePaginator
    {
        $filters = [];
        foreach ($options['filters'] ?? [] as $name => $filter) {
            if (isset($criteria[$name]) && $criteria[$name] !== '') {
                $filters[$name] = $criteria[$name];
            }
        }

        $query = $this->query($modelClass, $with, $filters);
        $search = $criteria['search'] ?? null;
        $searchable = $options['search'] ?? [];
        if (filled($search) && $searchable !== []) {
            $query->where(function (Builder $query) use ($search, $searchable): void {
                foreach ($searchable as $column) {
                    $query->orWhere($column, 'like', '%'.$search.'%');
                }
            });
        }

        $sort = $criteria['sort'] ?? null;
        $direction = $criteria['direction'] ?? 'asc';
        if ($sort !== null) {
            $query->orderBy($sort, $direction);
        } else {
            foreach ($defaultOrderBy as [$column, $order]) {
                $query->orderBy($column, $order);
            }
        }

        $perPage = $criteria['per_page'] ?? $options['per_page'] ?? 20;

        return $query->paginate($perPage)->withQueryString();
    }

    public function count(string $modelClass, array $filters = []): int
    {
        return $this->query($modelClass, filters: $filters)->count();
    }

    public function countByDate(string $modelClass, string $column, mixed $date, array $filters = []): int
    {
        return $this->query($modelClass, filters: $filters)->whereDate($column, $date)->count();
    }

    public function countsByDateRange(string $modelClass, string $column, string $from, string $to): Collection
    {
        return $this->query($modelClass)
            ->selectRaw("DATE({$column}) as date, COUNT(*) as aggregate")
            ->whereBetween($column, [$from, $to])
            ->groupBy('date')
            ->pluck('aggregate', 'date');
    }

    public function firstWhere(string $modelClass, string $column, mixed $value): ?Model
    {
        return $this->query($modelClass)->where($column, $value)->first();
    }

    public function create(string $modelClass, array $attributes): Model
    {
        return new $modelClass($attributes);
    }

    public function update(Model $model, array $attributes): bool
    {
        return $model->fill($attributes)->save();
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    private function query(string $modelClass, array $with = [], array $filters = [], array $orderBy = []): Builder
    {
        /** @var Model $model */
        $model = new $modelClass;
        $query = $model->newQuery()->with($with);

        foreach ($filters as $column => $value) {
            if (is_array($value)) {
                $query->whereIn($column, $value);
            } else {
                $query->where($column, $value);
            }
        }

        foreach ($orderBy as [$column, $direction]) {
            $query->orderBy($column, $direction);
        }

        return $query;
    }
}
