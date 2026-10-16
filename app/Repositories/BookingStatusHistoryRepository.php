<?php

namespace App\Repositories;

use App\Models\BookingStatusHistory;
use App\Models\Booking;
use App\Repositories\Contracts\BookingStatusHistoryRepositoryContract;

class BookingStatusHistoryRepository extends EloquentRepository implements BookingStatusHistoryRepositoryContract
{
    protected function modelClass(): string
    {
        return BookingStatusHistory::class;
    }

    /** @param array<string, mixed> $attributes */
    public function createForBooking(Booking $booking, array $attributes): BookingStatusHistory
    {
        return $booking->statusHistory()->create($attributes);
    }
}
