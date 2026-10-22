<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository extends EloquentRepository implements BookingRepositoryContract, BookingRepositoryInterface
{
    protected function modelClass(): string
    {
        return Booking::class;
    }

    public function findWithRestaurant(int $id): ?Booking
    {
        return Booking::query()->with('restaurant')->find($id);
    }

    public function findOrFail(int|string $id): Booking
    {
        return Booking::query()->findOrFail($id);
    }

    public function create(array $attributes): Booking
    {
        return Booking::query()->create($attributes);
    }

    public function bookingCodeExists(string $code): bool
    {
        return Booking::query()->where('booking_code', $code)->exists();
    }

    public function activePreOrderItems(array $ids): Collection
    {
        if ($ids === []) {
            return new Collection;
        }

        return MenuItem::query()->whereIn('id', $ids)->where('status', true)->where('is_must_try', true)->get();
    }

    public function activeRestaurants(): Collection
    {
        return Restaurant::query()->where('status', true)->orderBy('name')->get();
    }

    public function mustTryMenuItems(): Collection
    {
        return MenuItem::query()->where('status', true)->where('is_must_try', true)->orderBy('name')->get();
    }

    public function updateStatus(Booking $booking, string $status): void
    {
        $booking->update(['status' => $status]);
    }
}
