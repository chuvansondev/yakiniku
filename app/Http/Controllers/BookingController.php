<?php

namespace App\Http\Controllers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Jobs\SendBookingNotification;
use App\Services\SettingService;
use App\Models\Booking;

class BookingController extends Controller
{
    public function myBookings(Request $request): View
    {
        $bookings = $request->user()->bookings()
            ->with('restaurant')
            ->latest('booking_date')
            ->latest('booking_time')
            ->paginate(10);

        return view('frontend.bookings.index', compact('bookings'));
    }

    public function lookup(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'booking_code' => ['required', 'string', 'max:32'],
                'contact' => ['required', 'string', 'max:255'],
            ]);

            $booking = Booking::query()->with('restaurant')
                ->where('booking_code', strtoupper(trim($data['booking_code'])))
                ->where(fn ($query) => $query->where('email', $data['contact'])->orWhere('phone', $data['contact']))
                ->first();

            if (! $booking) {
                return back()->withInput()->withErrors(['booking_code' => __('Không tìm thấy đặt bàn. Vui lòng kiểm tra mã đặt bàn và email hoặc số điện thoại đã dùng khi đặt.')]);
            }

            $request->session()->put('looked_up_booking_id', $booking->id);

            return to_route('booking.lookup.result');
        }

        return view('frontend.bookings.lookup', ['booking' => null]);
    }

    public function lookupResult(Request $request): View
    {
        $bookingId = $request->session()->get('looked_up_booking_id');
        abort_if(! $bookingId, 404);
        $booking = Booking::query()->with('restaurant')->findOrFail($bookingId);

        return view('frontend.bookings.lookup', compact('booking'));
    }

    public function cancelManaged(Request $request, BookingService $bookingService, SettingService $settings, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 404);

        $status = $bookingService->requestCancellation($booking);
        $this->notifyAdminOfCancellationRequest($booking, $settings, $status);

        return to_route('booking.mine')->with($status === \App\Enums\BookingStatus::CancellationRequested ? 'booking_cancellation_requested' : 'booking_cancelled', true);
    }

    public function availability(Request $request, BookingService $bookingService): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id' => ['required', 'integer', 'exists:restaurants,id'],
            'booking_date' => ['nullable', 'date', 'after_or_equal:today'],
            'booking_time' => ['nullable', 'date_format:H:i'],
            'exclude_booking_id' => ['nullable', 'integer', 'exists:bookings,id'],
        ]);
        $validated['booking_date'] ??= null;
        $validated['booking_time'] ??= null;

        return response()->json([
            'tables' => $bookingService->tablesForRestaurant((int) $validated['restaurant_id'])->map(fn ($table) => [
                'code' => $table->code, 'floor' => $table->floor, 'capacity' => $table->capacity,
                'position' => $table->position,
            ])->values(),
            'unavailable' => $bookingService->unavailableTables(
                $validated['restaurant_id'], $validated['booking_date'], $validated['booking_time'], $validated['exclude_booking_id'] ?? null
            ),
        ]);
    }

    public function create(BookingService $bookingService): Response
    {
        $restaurants = $bookingService->formRestaurants();
        $mustTryMenuItems = $bookingService->mustTryMenuItems();

        $booking = null;

        if (session()->has('booking_success')) {
            $booking = $bookingService->findWithRestaurant((int) session('booking_id'));
        } else {
            session()->forget('booking_id');
        }

        $selectedRestaurantId = old('restaurant_id') ?: $restaurants->first()?->id;
        $customer = auth()->user();
        $tables = $bookingService->tablesForRestaurant($selectedRestaurantId);
        $tableCapacities = $tables->pluck('capacity', 'code')->all();
        $tableFloors = $tables->pluck('floor', 'code')->all();
        $floorCapacities = [];
        foreach ($tableCapacities as $code => $capacity) {
            $floorCapacities[$tableFloors[$code]] = ($floorCapacities[$tableFloors[$code]] ?? 0) + $capacity;
        }
        $maximumFloorCapacity = (int) ($floorCapacities ? max($floorCapacities) : 0);
        $unavailableTables = $bookingService->unavailableTables(
            $selectedRestaurantId, old('booking_date'), old('booking_time')
        );
        $bookingQr = $booking
            ? (new QRCode(new QROptions(['outputType' => QRCode::OUTPUT_MARKUP_SVG, 'scale' => 5])))->render($booking->booking_code)
            : null;

        return response()
            ->view('frontend.bookings.create', compact('booking', 'bookingQr', 'customer', 'maximumFloorCapacity', 'mustTryMenuItems', 'restaurants', 'tables', 'tableCapacities', 'tableFloors', 'unavailableTables', 'selectedRestaurantId'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, private')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function store(BookingRequest $request, BookingService $bookingService, SettingService $settings): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->user()) {
            $validated['customer_name'] = $request->user()->name;
            $validated['email'] = $request->user()->email;

            if (filled($validated['phone'] ?? null)) {
                $request->user()->forceFill(['phone' => $validated['phone']])->save();
            }
        }
        $tableFloors = $bookingService->tableFloors((int) $validated['restaurant_id']);
        $selectedFloors = collect($validated['table_codes'])->map(fn (string $code) => $tableFloors[$code] ?? null)->filter()->unique()->values();
        $validated['floor'] = (int) ($selectedFloors->first() ?? 1);

        if ($error = $bookingService->tableSelectionError($validated['number_of_guests'], $validated['table_codes'] ?? [], $validated['floor'], $validated['restaurant_id'], false)) {
            throw ValidationException::withMessages(['table_codes' => $error]);
        }

        $unavailableTables = $bookingService->unavailableTables(
            $validated['restaurant_id'], $validated['booking_date'], $validated['booking_time']
        );
        if (array_intersect($validated['table_codes'] ?? [], $unavailableTables)) {
            throw ValidationException::withMessages(['table_codes' => __('Một hoặc nhiều bàn vừa được khách khác đặt. Vui lòng chọn lại bàn.')]);
        }

        $booking = $bookingService->create($validated);

        $adminEmail = config('booking.admin_email') ?: $settings->get('email');
        $this->queueBookingEmail($booking, $booking->email, 'confirmation', false);
        $this->queueBookingEmail($booking, $adminEmail, 'confirmation', true);

        $request->session()->put('booking_id', $booking->id);

        return to_route('booking.create')
            ->with('booking_success', true)
            ->with('booking_code', $booking->booking_code);
    }

    private function queueBookingEmail(Booking $booking, ?string $recipient, string $type, bool $isAdmin): void
    {
        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            SendBookingNotification::dispatch($booking->id, $recipient, $type, $isAdmin);
        } catch (\Throwable $exception) {
            Log::error('Could not queue booking notification.', [
                'booking_id' => $booking->id,
                'recipient' => $recipient,
                'type' => $type,
                'is_admin' => $isAdmin,
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    public function cancel(Request $request, BookingService $bookingService, SettingService $settings): RedirectResponse
    {
        $bookingId = $request->session()->get('booking_id');

        abort_if($bookingId === null, 404);

        $booking = $bookingService->find((int) $bookingId);
        $status = $bookingService->requestCancellation($booking);
        $this->notifyAdminOfCancellationRequest($booking, $settings, $status);

        $request->session()->forget('booking_id');

        return to_route('booking.create')
            ->with($status === \App\Enums\BookingStatus::CancellationRequested ? 'booking_cancellation_requested' : 'booking_cancelled', true);
    }

    public function cancelFromConfirmation(Request $request, BookingService $bookingService, SettingService $settings): RedirectResponse
    {
        $bookingId = $request->session()->get('booking_id');
        abort_if($bookingId === null, 404);

        $booking = $bookingService->find((int) $bookingId);
        if ($request->user()) {
            abort_unless($booking->user_id === $request->user()->id, 404);
        } else {
            abort_unless($booking->user_id === null, 404);
        }
        $status = $bookingService->requestCancellation($booking);
        $this->notifyAdminOfCancellationRequest($booking, $settings, $status);

        return to_route('booking.create')
            ->with('booking_success', true)
            ->with($status === \App\Enums\BookingStatus::CancellationRequested ? 'booking_cancellation_requested' : 'booking_cancelled', true);
    }

    private function notifyAdminOfCancellationRequest(Booking $booking, SettingService $settings, \App\Enums\BookingStatus $status): void
    {
        if ($status !== \App\Enums\BookingStatus::CancellationRequested) {
            return;
        }

        $adminEmail = config('booking.admin_email') ?: $settings->get('email');
        if (! filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Booking cancellation request could not be emailed because no valid admin email is configured.', [
                'booking_id' => $booking->id,
            ]);

            return;
        }

        try {
            SendBookingNotification::dispatch($booking->id, $adminEmail, 'cancellation_request');
        } catch (\Throwable $exception) {
            Log::error('Could not queue booking cancellation request notification.', [
                'booking_id' => $booking->id,
                'recipient' => $adminEmail,
                'exception' => $exception->getMessage(),
            ]);
        }
    }
}
