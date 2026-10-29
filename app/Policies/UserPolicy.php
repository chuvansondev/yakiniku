<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return (bool) $actor->is_admin;
    }

    public function view(User $actor, User $user): bool
    {
        return (bool) $actor->is_admin;
    }

    public function create(User $actor): bool
    {
        return (bool) $actor->is_admin;
    }

    public function update(User $actor, User $user): bool
    {
        return (bool) $actor->is_admin && ! $user->is_admin;
    }

    public function delete(User $actor, User $user): bool
    {
        return (bool) $actor->is_admin && ! $user->is_admin && ! $user->is($actor);
    }

    public function deleteAny(User $actor): bool
    {
        return (bool) $actor->is_admin;
    }
}
