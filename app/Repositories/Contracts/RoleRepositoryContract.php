<?php

namespace App\Repositories\Contracts;

use App\Models\Role;
use App\Models\User;

interface RoleRepositoryContract extends RepositoryContract
{
    public function hasPermission(Role $role, string $permission): bool;

    public function userHasPermission(User $user, string $permission): bool;
}
