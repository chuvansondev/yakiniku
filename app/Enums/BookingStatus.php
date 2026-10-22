<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case CancellationRequested = 'cancellation_requested';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('Chờ xác nhận'),
            self::Confirmed => __('Đã xác nhận'),
            self::CancellationRequested => __('Yêu cầu hủy'),
            self::Cancelled => __('Đã hủy'),
            self::Completed => __('Đã hoàn thành'),
        };
    }
}
