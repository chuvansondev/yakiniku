<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryContract;

class CustomerRepository extends EloquentRepository implements CustomerRepositoryContract
{
    protected function modelClass(): string
    {
        return Customer::class;
    }
}