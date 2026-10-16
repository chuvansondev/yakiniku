<?php

namespace App\Filament\Resources\Pages\Concerns;

use App\Repositories\Contracts\RepositoryContract;
use LogicException;

trait UsesResourceRepository
{
    protected function getRepository(): RepositoryContract
    {
        $modelClass = static::getResource()::getModel();
        $repositoryClass = 'App\\Repositories\\'.class_basename($modelClass).'Repository';

        if (! class_exists($repositoryClass)) {
            throw new LogicException("Repository [{$repositoryClass}] was not found.");
        }

        return app($repositoryClass);
    }
}
