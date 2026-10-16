<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryContract;

class PermissionRepository extends EloquentRepository implements PermissionRepositoryContract
{
    protected function modelClass(): string
    {
        return Permission::class;
    }
}