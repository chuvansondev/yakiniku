<?php

namespace App\Repositories;

use App\Models\Lead;
use App\Contracts\Repositories\LeadRepositoryInterface;
use App\Repositories\Contracts\LeadRepositoryContract;

class LeadRepository extends EloquentRepository implements LeadRepositoryContract, LeadRepositoryInterface
{
    protected function modelClass(): string
    {
        return Lead::class;
    }

    public function create(array $attributes): Lead
    {
        /** @var Lead $lead */
        $lead = parent::create($attributes);

        return $lead;
    }
}
