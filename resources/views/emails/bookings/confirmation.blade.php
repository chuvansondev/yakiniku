@php($isEnglish = app()->getLocale() === 'en')
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isEnglish ? 'Booking details' : 'Thông tin đặt bàn' }}</title>
</head>
<body style="margin:0;padding:24px;background:#f6f2ee;color:#332a26;font-family:Arial,sans-serif;line-height:1.6">
    <table role="presentation" style="width:100%;max-width:600px;margin:0 auto;border-collapse:collapse;background:#fff">
        <tr>
            <td style="padding:28px 32px;background:#96262a;color:#fff">
                <h1 style="margin:0;font-size:22px">{{ $isAdmin ? ($isEnglish ? 'New table booking' : 'Có đặt bàn mới') : ($isEnglish ? 'Booking received' : 'Đã nhận đặt bàn') }}</h1>
            </td>
        </tr>
        <tr>
            <td style="padding:28px 32px">
                <p style="margin-top:0">{{ $isAdmin ? ($isEnglish ? 'A booking has been submitted.' : 'Một yêu cầu đặt bàn mới vừa được gửi.') : ($isEnglish ? 'Thank you. Here are your booking details.' : 'Cảm ơn bạn. Đây là thông tin đặt bàn của bạn.') }}</p>
                <p><strong>{{ $isEnglish ? 'Booking code' : 'Mã đặt bàn' }}:</strong> {{ $booking->booking_code }}</p>
                <p><strong>{{ $isEnglish ? 'Restaurant' : 'Nhà hàng' }}:</strong> {{ $booking->restaurant?->name }}</p>
                <p><strong>{{ $isEnglish ? 'Date' : 'Ngày' }}:</strong> {{ $booking->booking_date?->format('d/m/Y') }}</p>
                <p><strong>{{ $isEnglish ? 'Time' : 'Giờ' }}:</strong> {{ substr((string) $booking->booking_time, 0, 5) }}</p>
                <p><strong>{{ $isEnglish ? 'Guests' : 'Số khách' }}:</strong> {{ $booking->number_of_guests }}</p>
                <p><strong>{{ $isEnglish ? 'Name' : 'Tên khách' }}:</strong> {{ $booking->customer_name }}</p>
                <p><strong>{{ $isEnglish ? 'Phone' : 'Điện thoại' }}:</strong> {{ $booking->phone }}</p>
                <p><strong>Email:</strong> {{ $booking->email }}</p>
                @if ($booking->table_codes)
                    <p><strong>{{ $isEnglish ? 'Tables' : 'Bàn' }}:</strong> {{ implode(', ', $booking->table_codes) }}</p>
                @endif
                @if ($booking->note)
                    <p><strong>{{ $isEnglish ? 'Note' : 'Ghi chú' }}:</strong> {{ $booking->note }}</p>
                @endif
                <p style="margin-bottom:0;color:#75645a">{{ $isEnglish ? 'Current status' : 'Trạng thái hiện tại' }}: {{ $booking->status }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
