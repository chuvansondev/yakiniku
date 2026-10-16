<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryContract;

class BookingRepository extends EloquentRepository implements BookingRepositoryContract
{
    protected function modelClass(): string
    {
        return Booking::class;
    }
}