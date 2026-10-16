<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;

class UserRepository extends EloquentRepository implements UserRepositoryContract
{
    protected function modelClass(): string
    {
        return User::class;
    }
}