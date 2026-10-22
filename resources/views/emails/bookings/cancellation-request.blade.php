<h1>Khách yêu cầu hủy đặt bàn</h1>

<p>Một khách hàng đã gửi yêu cầu hủy đặt bàn. Bàn vẫn được giữ cho đến khi quản trị viên xác nhận hủy.</p>

<dl>
    <dt>Mã đặt bàn</dt><dd>{{ $booking->booking_code }}</dd>
    <dt>Khách hàng</dt><dd>{{ $booking->customer_name }}</dd>
    <dt>Điện thoại</dt><dd>{{ $booking->phone }}</dd>
    <dt>Email</dt><dd>{{ $booking->email ?: '—' }}</dd>
    <dt>Nhà hàng</dt><dd>{{ $booking->restaurant?->name ?: '—' }}</dd>
    <dt>Thời gian</dt><dd>{{ $booking->booking_date?->format('d/m/Y') }} lúc {{ $booking->booking_time }}</dd>
    <dt>Số khách</dt><dd>{{ $booking->number_of_guests }}</dd>
</dl>

<p><a href="{{ url('/admin/bookings/'.$booking->id.'/edit') }}">Mở đặt bàn trong trang quản trị</a>, sau đó chọn “Đã hủy” để xác nhận hủy hoặc “Đã xác nhận” để giữ nguyên lịch đặt.</p>
