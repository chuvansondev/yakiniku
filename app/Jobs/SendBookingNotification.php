<?php

namespace App\Jobs;

use App\Mail\BookingCancellationRequest;
use App\Mail\BookingConfirmation;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 60, 180];

    public function __construct(
        public readonly int $bookingId,
        public readonly string $recipient,
        public readonly string $type,
        public readonly bool $isAdmin = false,
    ) {
        $this->afterCommit();
    }

    public function handle(): void
    {
        $booking = Booking::query()->with('restaurant')->findOrFail($this->bookingId);

        $mailable = match ($this->type) {
            'confirmation' => new BookingConfirmation($booking, $this->isAdmin),
            'cancellation_request' => new BookingCancellationRequest($booking),
            default => throw new \InvalidArgumentException('Unsupported booking notification type.'),
        };

        Mail::to($this->recipient)->send($mailable);
    }

    public function failed(?\Throwable $exception): void
    {
        Log::error('Booking notification job failed.', [
            'booking_id' => $this->bookingId,
            'recipient' => $this->recipient,
            'type' => $this->type,
            'exception' => $exception?->getMessage(),
        ]);
    }
}
