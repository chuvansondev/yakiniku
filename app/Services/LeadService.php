<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Repositories\Contracts\LeadRepositoryContract;

class LeadService
{
    public function __construct(private readonly LeadRepositoryContract $leads, private readonly CustomerService $customers) {}

    public function register(array $attributes): Lead
    {
        $customer = $this->customers->resolve($attributes['name'], $attributes['email'] ?? null, $attributes['phone'] ?? null, null, 'lead');

        return $this->leads->create([...$attributes, 'customer_id' => $customer->id, 'status' => LeadStatus::New->value]);
    }
}
