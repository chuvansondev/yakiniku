<?php

namespace App\Filament\Pages;

use App\Enums\AppLocale;
use App\Enums\BookingStatus;
use App\Enums\LeadStatus;
use App\Models\Booking;
use App\Models\Lead;
use App\Services\AdminResourceService;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Collection;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    public int $bookingsCount = 0;

    public int $todayBookingsCount = 0;

    public int $pendingBookingsCount = 0;

    public int $pendingCancellationCount = 0;

    public int $newLeadsCount = 0;

    public Collection $bookingTrend;

    public Collection $recentBookings;

    public Collection $recentLeads;

    public function mount(): void
    {
        $resources = app(AdminResourceService::class);
        $today = today();
        $weekStart = $today->copy()->subDays(6);
        $bookingCountsByDate = $resources->countsByDateRange(Booking::class, 'booking_date', $weekStart->toDateString(), $today->toDateString());

        $this->bookingsCount = $resources->count(Booking::class);
        $this->todayBookingsCount = $resources->countByDate(Booking::class, 'booking_date', $today);
        $this->pendingBookingsCount = $resources->count(Booking::class, ['status' => BookingStatus::Pending->value]);
        $this->pendingCancellationCount = $resources->count(Booking::class, ['status' => BookingStatus::CancellationRequested->value]);
        $this->newLeadsCount = $resources->count(Lead::class, ['status' => LeadStatus::New->value]);
        $this->bookingTrend = collect(range(6, 0))->map(function (int $daysAgo) use ($today, $bookingCountsByDate): array {
            $date = $today->copy()->subDays($daysAgo);

            return [
                'date' => $date->toDateString(),
                'label' => $date->format('d/m'),
                'weekday' => $date->locale(AppLocale::Vietnamese->value)->isoFormat('dd'),
                'count' => (int) $bookingCountsByDate->get($date->toDateString(), 0),
            ];
        });
        $this->recentBookings = $resources->all(Booking::class, with: ['restaurant'], orderBy: [['id', 'desc']], limit: 5);
        $this->recentLeads = $resources->all(Lead::class, orderBy: [['created_at', 'desc']], filters: ['status' => LeadStatus::New->value], limit: 4);
    }

    public function getColumns(): int|array
    {
        return 1;
    }
}
