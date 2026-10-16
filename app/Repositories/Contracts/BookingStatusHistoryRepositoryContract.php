<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use App\Models\BookingStatusHistory;

interface BookingStatusHistoryRepositoryContract extends RepositoryContract
{
    /** @param array<string, mixed> $attributes */
    public function createForBooking(Booking $booking, array $attributes): BookingStatusHistory;
}
