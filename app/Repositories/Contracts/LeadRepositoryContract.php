<?php

namespace App\Repositories\Contracts;

use App\Models\Lead;

interface LeadRepositoryContract extends RepositoryContract
{
    public function create(array $attributes): Lead;
}
