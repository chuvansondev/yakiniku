<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\User;
use App\Repositories\Contracts\PermissionRepositoryContract;
use App\Repositories\Contracts\RoleRepositoryContract;
use Illuminate\Support\Str;

class PermissionService
{
    public function __construct(
        private readonly PermissionRepositoryContract $permissions,
        private readonly RoleRepositoryContract $roles,
    ) {}

    public function userHasPermission(User $user, string $permission): bool
    {
        return $this->roles->userHasPermission($user, $permission);
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): Permission
    {
        $attributes = $this->fillMissingSlug($attributes);

        /** @var Permission $permission */
        $permission = $this->permissions->create($attributes);

        return $permission;
    }

    /** @param array<string, mixed> $attributes */
    public function update(Permission $permission, array $attributes): Permission
    {
        if (blank($attributes['slug'] ?? null) && filled($attributes['name'] ?? $permission->name)) {
            $attributes['slug'] = Str::slug($attributes['name'] ?? $permission->name, '.');
        }

        /** @var Permission $permission */
        $permission = $this->permissions->update($permission, $attributes);

        return $permission;
    }

    /** @param array<string, mixed> $attributes
     *  @return array<string, mixed>
     */
    private function fillMissingSlug(array $attributes): array
    {
        if (blank($attributes['slug'] ?? null) && filled($attributes['name'] ?? null)) {
            $attributes['slug'] = Str::slug($attributes['name'], '.');
        }

        return $attributes;
    }
}
