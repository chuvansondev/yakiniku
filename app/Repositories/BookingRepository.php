<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Enums\BookingStatus;
use App\Repositories\Contracts\BookingRepositoryContract;
use App\Repositories\Contracts\BookingStatusHistoryRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository extends EloquentRepository implements BookingRepositoryContract
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
        return DB::transaction(function () use ($attributes): Booking {
            if (($attributes['status'] ?? null) === BookingStatus::Completed->value) {
                $attributes['completed_at'] ??= now();
            }

            $booking = Booking::query()->create($attributes);
            $this->recordStatusChange($booking, null, (string) $booking->status);

            return $booking;
        });
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
        if ($booking->status === $status) {
            return;
        }

        DB::transaction(function () use ($booking, $status): void {
            $fromStatus = (string) $booking->status;
            $booking->completed_at = $status === BookingStatus::Completed->value ? now() : null;
            $booking->update(['status' => $status]);
            $this->recordStatusChange($booking, $fromStatus, $status);
        });
    }

    public function updateBooking(Booking $booking, array $attributes): Booking
    {
        return DB::transaction(function () use ($booking, $attributes): Booking {
            $fromStatus = (string) $booking->status;
            if (array_key_exists('status', $attributes) && $fromStatus !== (string) $attributes['status']) {
                $booking->completed_at = $attributes['status'] === BookingStatus::Completed->value ? now() : null;
            }
            $booking->fill($attributes)->save();

            if (array_key_exists('status', $attributes) && $fromStatus !== (string) $booking->status) {
                $this->recordStatusChange($booking, $fromStatus, (string) $booking->status);
            }

            return $booking;
        });
    }

    private function recordStatusChange(Booking $booking, ?string $fromStatus, string $toStatus): void
    {
        $actor = auth()->user();

        app(BookingStatusHistoryRepositoryContract::class)->createForBooking($booking, [
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => auth()->id(),
            'changed_by_name' => $actor?->name ?? 'System',
            'source' => $actor?->is_admin ? 'admin' : ($actor ? 'customer' : 'system'),
            'change_type' => $fromStatus === null ? 'created' : 'status_change',
            'changed_at' => now(),
        ]);
    }
}
