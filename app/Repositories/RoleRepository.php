<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\RoleRepositoryContract;

class RoleRepository extends EloquentRepository implements RoleRepositoryContract
{
    protected function modelClass(): string
    {
        return Role::class;
    }

    public function hasPermission(Role $role, string $permission): bool
    {
        return $role->permissions()
            ->where('permissions.slug', $permission)
            ->exists();
    }

    public function userHasPermission(User $user, string $permission): bool
    {
        return $user->role()
            ->whereHas('permissions', fn ($query) => $query->where('permissions.slug', $permission))
            ->exists();
    }
}
