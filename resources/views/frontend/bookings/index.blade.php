@php($isEnglish = app()->getLocale() === 'en')
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isEnglish ? 'My bookings' : 'Lịch sử đặt bàn' }} | {{ config('app.name') }}</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#faf7f3;color:#332a26;font:15px/1.5 system-ui,sans-serif}main{width:min(1000px,calc(100% - 32px));margin:36px auto 60px}header{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px}h1{margin:0;color:#96262a;font:36px Georgia,serif}a{color:#8d2828}.button{padding:10px 15px;background:#a42127;color:#fff;text-decoration:none;border-radius:4px;font-weight:700}.notice{margin-bottom:16px;padding:12px;background:#eef7ed;color:#28602d}.booking{margin:14px 0;padding:19px 20px;background:white;border:1px solid #eadfd5}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.code{font-weight:750;letter-spacing:.06em}.status{display:inline-block;padding:5px 11px;border-radius:20px;background:#fff2d7;color:#8b5c00;font-size:13px;font-weight:700}.status--confirmed{background:#e9f7ed;color:#3e7950}.status--cancelled{background:#fff0ee;color:#ae3d32}.status--completed{background:#edf2f8;color:#496580}.details{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:7px 20px;margin-top:14px;color:#665b52}.cancel{margin-top:14px;padding:7px 12px;border:1px solid #d99c98;border-radius:4px;background:#fff;color:#a42127;cursor:pointer}nav{margin-top:24px}.empty{padding:28px;background:white;text-align:center}@media(max-width:580px){header{align-items:flex-start;flex-direction:column}h1{font-size:30px}.details{grid-template-columns:1fr}.booking{padding:15px}}
    </style>
</head>
<body>
@include('frontend.partials.header')
<main>
    <header><h1>{{ $isEnglish ? 'My bookings' : 'Lịch sử đặt bàn' }}</h1><a class="button" href="{{ route('booking.create') }}">{{ $isEnglish ? 'Book a table' : 'Đặt bàn' }}</a></header>
    @if (session('booking_cancelled'))<div class="notice" role="status">{{ $isEnglish ? 'The booking was cancelled.' : 'Đặt bàn đã được hủy.' }}</div>@endif
    @forelse ($bookings as $booking)
        @php($status = \App\Enums\BookingStatus::tryFrom($booking->status))
        <article class="booking">
            <div class="top"><span class="code">{{ $booking->booking_code }}</span><span class="status status--{{ $booking->status }}">{{ $status?->label() ?? $booking->status }}</span></div>
            <div class="details">
                <span>{{ $isEnglish ? 'Restaurant' : 'Nhà hàng' }}: {{ $booking->restaurant?->name }}</span>
                <span>{{ $isEnglish ? 'Date' : 'Ngày' }}: {{ $booking->booking_date?->format('d/m/Y') }} · {{ substr((string) $booking->booking_time, 0, 5) }}</span>
                <span>{{ $isEnglish ? 'Guests' : 'Số khách' }}: {{ $booking->number_of_guests }}</span>
                <span>{{ $isEnglish ? 'Contact' : 'Liên hệ' }}: {{ $booking->phone }} · {{ $booking->email }}</span>
                @if ($booking->table_codes)<span>{{ $isEnglish ? 'Tables' : 'Bàn' }}: {{ implode(', ', $booking->table_codes) }}</span>@endif
                @if ($booking->note)<span>{{ $isEnglish ? 'Note' : 'Ghi chú' }}: {{ $booking->note }}</span>@endif
            </div>
            @if (in_array($booking->status, ['pending', 'confirmed'], true))
                <form method="POST" action="{{ route('booking.mine.cancel', $booking) }}" onsubmit="return confirm('{{ $isEnglish ? 'Cancel this booking?' : 'Bạn muốn hủy đặt bàn này?' }}')">
                    @csrf<button class="cancel" type="submit">{{ $isEnglish ? 'Cancel booking' : 'Hủy đặt bàn' }}</button>
                </form>
            @endif
        </article>
    @empty
        <div class="empty">{{ $isEnglish ? 'You have no bookings yet.' : 'Bạn chưa có lịch sử đặt bàn.' }} <a href="{{ route('booking.create') }}">{{ $isEnglish ? 'Make a booking' : 'Đặt bàn ngay' }}</a></div>
    @endforelse
    <nav aria-label="Pagination">{{ $bookings->links() }}</nav>
</main>
@include('frontend.partials.footer')
</body>
</html>
