<?php

namespace App\Repositories;

use App\Models\Lead;
use App\Repositories\Contracts\LeadRepositoryContract;

class LeadRepository extends EloquentRepository implements LeadRepositoryContract
{
    protected function modelClass(): string
    {
        return Lead::class;
    }
}