@php
    $isEnglish = app()->getLocale() === 'en';
    $brand = setting('header_brand_name'.($isEnglish ? '_en' : '')) ?: config('app.name');
    $success = session('booking_success') && $booking;
@endphp
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    @include('frontend.partials.assets')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <meta name="description" content="{{ $isEnglish ? 'Reserve your table at '.$brand.'.' : 'Đặt bàn tại '.$brand.'.' }}">
    <title>{{ $isEnglish ? 'Book a Table' : 'Đặt bàn' }} | {{ $brand }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG_B.woff2') }}') format('woff2');font-weight:700;font-display:swap}
        @font-face{font-family:'Dense';src:url('{{ asset('fonts/Dense.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        .booking-page{--red:#a42127;--ink:#332a26;--muted:#75645a;min-height:100vh;background:#fbf9f6!important;color:var(--ink)!important;color-scheme:light;font-family:'UTM BryantLG',Arial,sans-serif}.booking-page *{box-sizing:border-box}
        .booking-hero{position:relative;display:grid;min-height:270px;place-items:center;overflow:hidden;background:linear-gradient(120deg,#241714,#493027 52%,#231a17);color:#fff;text-align:center}.booking-hero:before{position:absolute;inset:0;opacity:.24;background:radial-gradient(ellipse at 50% 110%,#ba7b4a 0,transparent 55%),repeating-linear-gradient(135deg,transparent 0 8px,rgba(255,255,255,.035) 8px 9px);content:''}.booking-hero:after{position:absolute;inset:18px;border:1px solid rgba(230,200,161,.3);content:''}.booking-hero__content{position:relative;padding:35px 20px}.booking-hero__eyebrow{margin:0 0 10px;color:#dfbd96;font-size:12px;font-weight:700;letter-spacing:.28em;text-transform:uppercase}.booking-hero h1{margin:0;color:#fff;font-family:'UTM Pacific','Dense',serif;font-size:clamp(42px,6vw,68px);font-weight:400;letter-spacing:.035em;line-height:1.1}.booking-hero__sub{max-width:570px;margin:14px auto 0;color:#e7d4c0;font-size:17px;line-height:1.6}
        .booking-wrap{width:min(720px,calc(100% - 32px));margin:auto;padding:38px 0 58px}.booking-layout{display:block}.booking-aside{display:none}
        .booking-card{padding:24px 28px;border:1px solid #e8dfd7;background:#fff;box-shadow:0 12px 34px rgba(58,39,28,.06)}.booking-card__heading{margin:0 0 20px;color:var(--red);font-family:'UTM Pacific','Dense',serif;font-size:27px;font-weight:400}.booking-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:13px 16px}.booking-field{display:grid;gap:5px}.booking-field--wide{grid-column:1/-1}.booking-field label,.booking-label{color:#463d38;font-size:12px;font-weight:700}.booking-field input,.booking-field select,.booking-field textarea{width:100%;min-height:39px;padding:8px 10px;border:1px solid #d9d9d9;border-radius:3px;background:#fff;color:#332a26;font:inherit;font-size:13px}.booking-field textarea{min-height:65px;resize:vertical}.booking-field input:focus,.booking-field select:focus,.booking-field textarea:focus{outline:2px solid rgba(164,33,39,.18);border-color:var(--red)}.booking-error{color:#a42127;font-size:12px}.booking-hint{margin:0;color:#8b7b70;font-size:12px;line-height:1.45}.booking-divider{grid-column:1/-1;margin:3px 0 -1px;padding-bottom:8px;border-bottom:1px solid #eee7e1;color:#775b47;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase}.booking-table-options{grid-column:1/-1;display:grid;gap:0}.booking-floor-map{display:grid;grid-template-columns:repeat(3,minmax(34px,1fr));grid-auto-rows:43px;align-items:center;justify-items:center;gap:9px;padding:17px;border:1px solid #999;background:#fff;position:relative}.booking-floor-map:before{position:absolute;inset:8px;border:1px dashed #e8e2dc;content:"";pointer-events:none}.booking-floor-map[hidden]{display:none}.booking-floor-title{grid-column:1/-1;grid-row:1;margin:0;color:#332a26;font-size:12px;font-weight:700}.booking-table{position:relative;display:grid;width:42px;height:38px;place-items:center;background:#aaa;color:#fff;font-size:12px;font-weight:700;cursor:pointer;transition:transform .15s,background .15s,box-shadow .15s}.booking-table:before,.booking-table:after{position:absolute;inset:-5px -7px;border-left:5px dotted #b9b9b9;border-right:5px dotted #b9b9b9;content:"";pointer-events:none}.booking-table span{position:relative;z-index:1}.booking-table input{position:absolute;inset:0;width:100%;height:100%;margin:0;opacity:0;cursor:pointer}.booking-table:hover{transform:translateY(-2px)}.booking-table:has(input:checked){background:#6d3d2c;box-shadow:0 0 0 3px #eadbd2}.booking-table.is-unavailable{background:#d4d4d4;color:#fff;cursor:not-allowed;opacity:.58}.booking-table.is-unavailable input{cursor:not-allowed}.booking-table-legend{display:flex;flex-wrap:wrap;justify-content:center;gap:14px;margin:12px 0 0;color:#665b52;font-size:11px}.booking-table-legend span{display:inline-flex;align-items:center;gap:6px}.booking-table-legend i{width:12px;height:12px;background:#aaa}.booking-table-legend .legend-selected{background:#6d3d2c}.booking-table-legend .legend-unavailable{background:#d4d4d4}.booking-preorder{display:grid;gap:5px;padding:9px;background:#f2f2f2}.booking-preorder label{display:flex;align-items:center;gap:8px;padding:5px;color:#555;font-size:12px}.booking-preorder input{width:auto;min-height:auto;accent-color:#a42127}.booking-terms{grid-column:1/-1;padding-top:2px}.booking-terms h3{margin:0 0 7px;font-size:12px}.booking-terms p{margin:0;color:#71665d;font-size:11px;line-height:1.5}.booking-consent{display:flex;align-items:flex-start;gap:8px;margin-top:10px;color:#4c4640;font-size:11px;line-height:1.4}.booking-consent input{width:auto;min-height:auto;margin:2px 0 0;accent-color:#edb915}.booking-consent a{color:#2873a8}.booking-submit{grid-column:1/-1;display:flex;align-items:center;justify-content:center;gap:12px;margin-top:2px}.booking-button{display:inline-flex;min-width:108px;min-height:39px;align-items:center;justify-content:center;padding:9px 19px;border:1px solid #d5d5d5;border-radius:4px;background:#fff;color:#665f59;font:inherit;font-size:12px;text-decoration:none;cursor:pointer}.booking-button--confirm{border-color:#edb915;background:#edb915;color:#2f2918;font-weight:700}.booking-alert{margin:0 0 17px;padding:12px 14px;border:1px solid #eccac5;background:#fff5f3;color:#8f2427;font-size:13px;line-height:1.5}.booking-confirmation{max-width:600px;margin:auto;padding:35px;border:1px solid #e8dfd7;background:#fff;text-align:center}.booking-confirmation__icon{display:grid;width:48px;height:48px;place-items:center;margin:0 auto 14px;border-radius:50%;background:#edf5e9;color:#427448;font-size:25px}.booking-confirmation h2{margin:0;color:var(--red);font-family:'UTM Pacific','Dense',serif;font-size:32px;font-weight:400}.booking-confirmation p{margin:12px 0;color:var(--muted);font-size:15px;line-height:1.6}.booking-code{display:inline-block;margin:8px 0;padding:9px 16px;border:1px dashed #b99675;color:#60483a;font-weight:700;letter-spacing:.12em}.booking-confirmation__actions{display:flex;flex-wrap:wrap;justify-content:center;gap:10px}
        .booking-confirmation{max-width:500px;padding:18px 22px 24px;text-align:left}.booking-confirmation__top{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px}.booking-status{display:inline-flex;align-items:center;padding:5px 11px;border-radius:20px;background:#fff2d7;color:#8b5c00;font-size:12px;font-weight:700}.booking-status--confirmed{background:#e9f7ed;color:#3e9952}.booking-status--cancelled{background:#fff0ee;color:#ae3d32}.booking-status--completed{background:#edf2f8;color:#496580}.booking-cancel-link{padding:4px 0;border:0;background:none;color:#d56b68;font:inherit;font-size:12px;cursor:pointer}.booking-cancel-notice{margin:0 0 12px;padding:8px 10px;border-radius:4px;background:#fff2ef;color:#a63a2e;font-size:13px}.booking-qr{display:flex;justify-content:center;max-width:100%;margin:2px auto 18px;overflow:hidden}.booking-qr img{display:block;width:142px;height:142px;max-width:100%;object-fit:contain}.booking-details{padding:12px 10px;border-top:1px solid #eee;border-bottom:1px solid #eee}.booking-details summary{width:max-content;margin:12px auto 0;color:#488fc1;font-size:12px;cursor:pointer;list-style:none}.booking-details summary::-webkit-details-marker{display:none}.booking-details summary:before{content:'⌃  '}.booking-details:not([open]) summary:before{content:'⌄  '}.booking-details dl{display:grid;grid-template-columns:minmax(110px,1fr) minmax(0,1fr);gap:7px 12px;margin:0}.booking-details dt{color:#514b47;font-size:12px}.booking-details dd{margin:0;color:#332a26;font-size:12px;font-weight:700;text-align:right;overflow-wrap:anywhere}.booking-confirmation__actions{margin-top:16px}.booking-button--home{width:100%;min-height:42px;border-color:#edb915;background:#edb915;color:#332a26;font-size:13px;font-weight:700}.booking-floor-tabs{display:flex;justify-content:center;gap:38px;margin:0 0 10px}.booking-floor-tab{padding:4px 8px;border:0;border-bottom:1px solid transparent;background:none;color:#332a26;font:inherit;font-size:12px;cursor:pointer}.booking-floor-tab[aria-selected="true"]{border-color:#332a26;font-weight:700}.booking-floor-map{grid-template-columns:repeat(3,minmax(46px,1fr));grid-auto-rows:50px;gap:12px;padding:16px 24px}.booking-table{width:38px;height:34px;background:#6d3d2c;font-size:11px}.booking-table:before,.booking-table:after{display:none}.booking-table .booking-seats{position:absolute;top:50%;left:-18px;display:grid;grid-auto-flow:column;grid-template-rows:repeat(3,8px);align-content:center;gap:3px;transform:translateY(-50%);pointer-events:none}.booking-table .booking-seats--right{left:auto;right:-18px}.booking-table .booking-seat-dot{position:static;z-index:2;width:8px;height:8px;border-radius:50%;background:#333}.booking-table[data-capacity="2"] .booking-seats,.booking-table[data-capacity="4"] .booking-seats{grid-template-rows:repeat(2,8px);align-content:center;gap:5px}.booking-table[data-capacity="2"] .booking-seats{grid-template-rows:8px;gap:0}.booking-table:has(input:checked){background:#6d3d2c;box-shadow:0 0 0 2px #d5c5ba}.booking-table.is-unavailable{background:#bcbcbc;opacity:1}.booking-table.is-unavailable .booking-seat-dot{background:#bcbcbc}.booking-table-legend i{background:#6d3d2c}.booking-table-legend .legend-unavailable{background:#bcbcbc}.booking-table-legend .legend-selected{background:#6d3d2c;box-shadow:0 0 0 2px #d5c5ba}
        .booking-card{background:#fff!important;color:#332a26!important}.booking-field label,.booking-terms h3{color:#463d38!important}.booking-field input,.booking-field select,.booking-field textarea{background:#fff!important;color:#332a26!important}
        @media(max-width:600px){.booking-hero{min-height:190px}.booking-wrap{width:calc(100% - 24px);padding:23px 0 38px}.booking-card{padding:18px 15px}.booking-grid{gap:11px 10px}.booking-floor-map{min-height:112px;padding:13px 20px;gap:9px}.booking-table{width:34px;height:30px;font-size:10px}.booking-table .booking-seats{left:-15px;grid-template-rows:repeat(3,7px);gap:2px}.booking-table .booking-seats--right{left:auto;right:-15px}.booking-table .booking-seat-dot{width:7px;height:7px}.booking-table[data-capacity="2"] .booking-seats{grid-template-rows:7px;gap:0}.booking-table[data-capacity="4"] .booking-seats{grid-template-rows:repeat(2,7px);gap:4px}.booking-floor-tabs{margin-bottom:8px}}
    </style>
</head>
<body>
    @if ($success)
        <script>
            window.addEventListener('pageshow', (event) => {
                if (event.persisted) window.location.reload();
            });
        </script>
    @endif
    @include('frontend.partials.header')
    <main class="booking-page">
        <section class="booking-hero"><div class="booking-hero__content"><p class="booking-hero__eyebrow">{{ $brand }}</p><h1>{{ $isEnglish ? 'Book a Table' : 'Đặt bàn' }}</h1><p class="booking-hero__sub">{{ $isEnglish ? 'Choose a restaurant and a time. We look forward to welcoming you.' : 'Chọn nhà hàng và thời gian phù hợp. Chúng tôi mong được đón tiếp bạn.' }}</p></div></section>
        <div class="booking-wrap">
            @if ($success)
                <section class="booking-confirmation" aria-live="polite">
                    @php
                        $bookingStatus = \App\Enums\BookingStatus::tryFrom($booking->status);
                        $bookingStatusLabel = $bookingStatus?->label() ?? $booking->status;
                    @endphp
                    @if (session('booking_cancelled'))<p class="booking-cancel-notice">{{ $isEnglish ? 'Your booking has been cancelled.' : 'Đặt bàn đã được hủy.' }}</p>@endif
                    @if (session('booking_cancellation_requested') || $booking->status === 'cancellation_requested')<p class="booking-cancel-notice">{{ $isEnglish ? 'Your cancellation request was sent. The booking remains active until the restaurant confirms it.' : 'Yêu cầu hủy đã được gửi. Đặt bàn vẫn còn hiệu lực cho đến khi nhà hàng xác nhận.' }}</p>@endif
                    <div class="booking-confirmation__top"><span class="booking-status booking-status--{{ $booking->status }}">{{ $isEnglish ? match($booking->status) { 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'completed' => 'Completed', default => 'Pending confirmation' } : $bookingStatusLabel }}</span>
                        @if (in_array($booking->status, ['pending', 'confirmed'], true))<form method="post" action="{{ route('booking.cancel-confirmation') }}" onsubmit="return confirm('{{ $isEnglish ? 'Cancel this booking?' : 'Bạn có chắc muốn hủy đặt bàn này?' }}')">@csrf<button class="booking-cancel-link" type="submit">{{ $isEnglish ? 'Cancel booking' : 'Hủy đặt bàn' }}</button></form>@endif
                    </div>
                    @if ($bookingQr)<div class="booking-qr"><img src="{{ $bookingQr }}" alt="{{ $isEnglish ? 'Booking QR code' : 'Mã QR đặt bàn' }}" width="142" height="142"></div>@endif
                    <details class="booking-details" open><summary>{{ $isEnglish ? 'Hide details' : 'Ẩn thông tin' }}</summary><dl>
                        <dt>{{ $isEnglish ? 'Booking code' : 'Mã đặt bàn' }}</dt><dd>{{ $booking->booking_code }}</dd>
                        <dt>{{ $isEnglish ? 'Date and time' : 'Thời gian đặt' }}</dt><dd>{{ $booking->booking_date?->format('d/m/Y') }} · {{ substr((string) $booking->booking_time, 0, 5) }}</dd>
                        <dt>{{ $isEnglish ? 'Guests' : 'Số lượng người' }}</dt><dd>{{ $booking->number_of_guests }} {{ $isEnglish ? 'guests' : 'người' }}</dd>
                        <dt>{{ $isEnglish ? 'Name' : 'Họ và tên' }}</dt><dd>{{ $booking->customer_name }}</dd>
                        <dt>{{ $isEnglish ? 'Phone' : 'Số điện thoại' }}</dt><dd>{{ $booking->phone }}</dd>
                        <dt>{{ $isEnglish ? 'Email' : 'Email' }}</dt><dd>{{ $booking->email }}</dd>
                        @if ($booking->note)<dt>{{ $isEnglish ? 'Note' : 'Ghi chú' }}</dt><dd>{{ $booking->note }}</dd>@endif
                    </dl></details>
                    <div class="booking-confirmation__actions"><a class="booking-button booking-button--home" href="{{ route('home') }}">{{ $isEnglish ? 'BACK TO HOME' : 'Về trang chủ' }}</a></div>
                </section>
            @else
                <div class="booking-layout">
                    <aside class="booking-aside"><p class="booking-aside__kicker">{{ $isEnglish ? 'Gather around the grill' : 'Quây quần bên bếp nướng' }}</p><h2>{{ $isEnglish ? 'Make time for a good meal.' : 'Dành thời gian cho một bữa ngon.' }}</h2><p>{{ $isEnglish ? 'Tell us when you would like to visit and how many guests are joining. We will get back to you to confirm.' : 'Cho chúng tôi biết thời gian ghé thăm và số lượng khách. Nhà hàng sẽ liên hệ để xác nhận với bạn.' }}</p><div class="booking-aside__note">{{ $isEnglish ? 'For changes to an existing reservation, please contact the restaurant directly.' : 'Nếu cần thay đổi đặt bàn đã có, vui lòng liên hệ trực tiếp nhà hàng.' }}</div></aside>
                    <section class="booking-card">
                        <h2 class="booking-card__heading">{{ $isEnglish ? 'Reservation details' : 'Thông tin đặt bàn' }}</h2>
                        @if ($errors->any())<div class="booking-alert" role="alert">{{ $isEnglish ? 'Please review the highlighted details and try again.' : 'Vui lòng kiểm tra lại thông tin đặt bàn.' }}</div>@endif
                        <form method="post" action="{{ route('booking.store') }}" class="booking-grid">
                            @csrf
                            <div class="booking-field booking-field--wide"><label for="restaurant_id">{{ $isEnglish ? 'Restaurant' : 'Nhà hàng' }} *</label><select id="restaurant_id" name="restaurant_id" required><option value="">{{ $isEnglish ? 'Choose a restaurant' : 'Chọn nhà hàng' }}</option>@foreach ($restaurants as $restaurant)<option value="{{ $restaurant->id }}" @selected((string) old('restaurant_id', $selectedRestaurantId) === (string) $restaurant->id)>{{ $isEnglish ? ($restaurant->name_en ?: $restaurant->name) : $restaurant->name }}</option>@endforeach</select>@error('restaurant_id')<span class="booking-error">{{ $message }}</span>@enderror</div>
                            <div class="booking-field"><label for="booking_date">{{ $isEnglish ? 'Dining date' : 'Ngày dùng bữa' }} *</label><input id="booking_date" type="date" name="booking_date" min="{{ now()->toDateString() }}" value="{{ old('booking_date') }}" required>@error('booking_date')<span class="booking-error">{{ $message }}</span>@enderror<span class="booking-hint">{{ $isEnglish ? 'We hold your table for up to 15 minutes after your reservation time.' : 'Nhà hàng giữ bàn tối đa 15 phút sau giờ đã đặt.' }}</span></div>
                            <div class="booking-field"><label for="booking_time">{{ $isEnglish ? 'Time' : 'Giờ đến' }} *</label><input id="booking_time" type="time" name="booking_time" value="{{ old('booking_time') }}" required>@error('booking_time')<span class="booking-error">{{ $message }}</span>@enderror</div>
                            <div class="booking-field"><label for="number_of_guests">{{ $isEnglish ? 'Number of guests' : 'Số lượng người' }} *</label><input id="number_of_guests" type="number" name="number_of_guests" min="1" max="100" value="{{ old('number_of_guests', 2) }}" required>@error('number_of_guests')<span class="booking-error">{{ $message }}</span>@enderror</div>
                            <div class="booking-field"><label for="customer_name">{{ $isEnglish ? 'Your name' : 'Họ tên' }} *</label><input id="customer_name" type="text" name="customer_name" maxlength="255" autocomplete="name" placeholder="{{ $isEnglish ? 'Enter your name' : 'Nhập họ tên' }}" value="{{ old('customer_name', $customer?->name) }}" required>@error('customer_name')<span class="booking-error">{{ $message }}</span>@enderror</div>
                            <div class="booking-field"><label for="phone">{{ $isEnglish ? 'Phone number' : 'Số điện thoại' }} *</label><input id="phone" type="tel" name="phone" maxlength="30" autocomplete="tel" placeholder="{{ $isEnglish ? 'Enter your phone number' : 'Nhập số điện thoại' }}" value="{{ old('phone', $customer?->phone) }}" required>@error('phone')<span class="booking-error">{{ $message }}</span>@enderror</div>
                            <div class="booking-field booking-field--wide"><label for="email">{{ $isEnglish ? 'Email' : 'Email' }} *</label><input id="email" type="email" name="email" maxlength="255" autocomplete="email" value="{{ old('email', $customer?->email) }}" required>@error('email')<span class="booking-error">{{ $message }}</span>@enderror</div>
                            <div class="booking-field booking-field--wide"><label>{{ $isEnglish ? 'Pre-order' : 'Gọi món trước' }}</label><div class="booking-preorder">@forelse ($mustTryMenuItems as $item)<label><input type="checkbox" name="pre_order_items[]" value="{{ $item->id }}" @checked(in_array($item->id, old('pre_order_items', [])))><span>{{ $isEnglish ? ($item->name_en ?: $item->name) : $item->name }} <small>({{ $isEnglish ? 'Must try' : 'Khuyên mời/Combo Must try' }})</small></span></label>@empty<p class="booking-hint">{{ $isEnglish ? 'Pre-order selections will appear here.' : 'Các món khách bấm chọn ở các trang trước đó (Khuyên mời/Combo Must try)' }}</p>@endforelse</div></div>
                            @if ($maximumFloorCapacity > 0)
                                <div class="booking-divider">{{ $isEnglish ? 'Choose a table' : 'Chọn bàn' }}</div>
                                <p class="booking-hint booking-field--wide">{{ $isEnglish ? 'Switch floors and select enough seats for your party.' : 'Chuyển tầng và chọn đủ chỗ cho số khách.' }}</p>
                                <div class="booking-table-options" id="bookingTables"><div class="booking-floor-tabs" role="tablist" aria-label="{{ $isEnglish ? 'Restaurant floors' : 'Tầng nhà hàng' }}"><button class="booking-floor-tab" type="button" role="tab" data-floor-tab="1" aria-selected="true">{{ $isEnglish ? 'Floor 1' : 'Tầng 1' }}</button><button class="booking-floor-tab" type="button" role="tab" data-floor-tab="2" aria-selected="false">{{ $isEnglish ? 'Floor 2' : 'Tầng 2' }}</button></div>@foreach (collect($tableFloors)->unique()->sort() as $floorNumber)<div class="booking-floor-map" data-floor-map="{{ $floorNumber }}" @if ((int) $floorNumber !== 1) hidden @endif><h4 class="booking-floor-title">{{ $isEnglish ? 'Floor ' : 'Tầng ' }}{{ $floorNumber }}</h4>@foreach ($tableCapacities as $code => $capacity)@if ((int) $tableFloors[$code] === (int) $floorNumber)<label class="booking-table" data-floor="{{ $tableFloors[$code] }}" data-capacity="{{ $capacity }}" data-position="{{ $tables->firstWhere('code', $code)?->position ?? 0 }}" title="{{ $isEnglish ? 'Capacity: ' : 'Sức chứa: ' }}{{ $capacity }}"><input type="checkbox" name="table_codes[]" value="{{ $code }}" @checked(in_array($code, old('table_codes', [])))><span class="booking-seats">@for ($seat = 0; $seat < ceil($capacity / 2); $seat++)<span class="booking-seat-dot"></span>@endfor</span><span class="booking-seats booking-seats--right">@for ($seat = 0; $seat < floor($capacity / 2); $seat++)<span class="booking-seat-dot"></span>@endfor</span><span>{{ $code }}</span></label>@endif @endforeach</div>@endforeach<p id="bookingCapacity" class="booking-hint" aria-live="polite"></p><div class="booking-table-legend"><span><i></i>{{ $isEnglish ? 'Available' : 'Còn trống' }}</span><span><i class="legend-unavailable"></i>{{ $isEnglish ? 'Booked' : 'Đã đặt' }}</span><span><i class="legend-selected"></i>{{ $isEnglish ? 'Selected' : 'Đang chọn' }}</span></div></div>
                                @error('table_codes')<span class="booking-error booking-field--wide">{{ $message }}</span>@enderror
                                @error('table_codes.*')<span class="booking-error booking-field--wide">{{ $message }}</span>@enderror
                            @else
                                <input type="hidden" name="table_codes" value="">
                                <div class="booking-divider">{{ $isEnglish ? 'Table selection' : 'Chọn bàn' }}</div>
                                <p class="booking-hint booking-field--wide">{{ $isEnglish ? '' : '' }}</p>
                            @endif
                            <div class="booking-field"><label for="note">{{ $isEnglish ? 'Note' : 'Ghi chú' }}</label><textarea id="note" name="note" maxlength="2000" placeholder="{{ $isEnglish ? 'Anything we should know?' : 'Ghi chú thêm (nếu có)' }}">{{ old('note') }}</textarea>@error('note')<span class="booking-error">{{ $message }}</span>@enderror</div>
                            <div class="booking-terms" id="booking-terms"><h3>{{ $isEnglish ? 'Terms & reservation policy' : 'Điều khoản và lưu ý đặt bàn' }}</h3><p>{{ $isEnglish ? 'We will hold your table for 15 minutes after your reservation time. Please contact us for groups of 15 or more or special requests.' : 'Nhà hàng giữ bàn tối đa 15 phút sau giờ đã đặt; sau thời gian này xin phép phục vụ khách tiếp theo. Vui lòng xác nhận đặt bàn bằng điện thoại nếu đặt từ 15 khách trở lên hoặc có yêu cầu đặc biệt.' }}</p><label class="booking-consent"><input type="checkbox" name="terms_accepted" value="1" required><span>{{ $isEnglish ? 'I have read and agree to the ' : 'Tôi đã đọc và đồng ý với ' }}<a href="#booking-terms">{{ $isEnglish ? 'reservation terms' : 'điều khoản đặt bàn' }}</a>.</span></label></div>
                            <div class="booking-submit"><a class="booking-button" href="{{ route('home') }}">{{ $isEnglish ? 'CANCEL' : 'Hủy bỏ' }}</a><button class="booking-button booking-button--confirm" type="submit">{{ $isEnglish ? 'CONFIRM' : 'Xác nhận' }}</button></div>
                        </form>
                    </section>
                </div>
            @endif
        </div>
    </main>
    @include('frontend.partials.footer')
    @if (! $success && $maximumFloorCapacity > 0)
        <script>
            (() => {
                const restaurant = document.getElementById('restaurant_id');
                let activeFloor = '1';
                const date = document.getElementById('booking_date');
                const time = document.getElementById('booking_time');
                const guests = document.getElementById('number_of_guests');
                const tables = document.getElementById('bookingTables');
                if (!restaurant || !tables) return;
                let renderedRestaurant = restaurant.value;
                const renderFloorTabs = (floors) => {
                    const tabs = tables.querySelector('.booking-floor-tabs');
                    tabs.replaceChildren(...floors.map((number) => {
                        const tab = document.createElement('button');
                        tab.type = 'button';
                        tab.className = 'booking-floor-tab';
                        tab.setAttribute('role', 'tab');
                        tab.dataset.floorTab = String(number);
                        tab.setAttribute('aria-selected', String(number) === activeFloor ? 'true' : 'false');
                        tab.textContent = `{{ $isEnglish ? 'Floor ' : 'Tầng ' }}${number}`;
                        tab.addEventListener('click', () => {
                            activeFloor = String(number);
                            filterTables();
                        });
                        return tab;
                    }));
                };
                tables.querySelectorAll('[data-floor-tab]').forEach((tab) => tab.addEventListener('click', () => {
                    activeFloor = tab.dataset.floorTab;
                    filterTables();
                }));
                const renderRestaurantTables = (items) => {
                    const floors = [...new Set(items.map((item) => Number(item.floor)))].sort((a, b) => a - b);
                    if (floors.length === 0) floors.push(1, 2, 3, 4, 5);
                    const currentFloor = floors.includes(Number(activeFloor)) ? Number(activeFloor) : (floors[0] || 1);
                    activeFloor = String(currentFloor);
                    tables.querySelectorAll('[data-floor-map]').forEach((map) => map.remove());

                    floors.forEach((number) => {
                        const map = document.createElement('div');
                        map.className = 'booking-floor-map';
                        map.dataset.floorMap = String(number);
                        map.hidden = false;
                        const heading = document.createElement('h4');
                        heading.className = 'booking-floor-title';
                        heading.textContent = `{{ $isEnglish ? 'Floor ' : 'Tầng ' }}${number}`;
                        map.append(heading);
                        items.filter((item) => Number(item.floor) === number).forEach((item) => {
                            const label = document.createElement('label');
                            label.className = 'booking-table';
                            label.dataset.floor = String(number);
                            label.dataset.position = String(item.position || 0);
                            label.dataset.capacity = String(item.capacity || 0);
                            label.title = `{{ $isEnglish ? 'Capacity: ' : 'Sức chứa: ' }}${item.capacity}`;
                            const input = document.createElement('input');
                            input.type = 'checkbox';
                            input.name = 'table_codes[]';
                            input.value = item.code;
                            const name = document.createElement('span');
                            name.textContent = item.code;
                            const addSeatDots = (count, sideClass) => {
                                const side = document.createElement('span');
                                side.className = `booking-seats ${sideClass}`;
                                for (let seat = 0; seat < count; seat++) {
                                    const dot = document.createElement('span');
                                    dot.className = 'booking-seat-dot';
                                    side.append(dot);
                                }
                                return side;
                            };
                            const capacity = Number(item.capacity || 0);
                            label.append(input, addSeatDots(Math.ceil(capacity / 2), ''), addSeatDots(Math.floor(capacity / 2), 'booking-seats--right'), name);
                            const position = Number(item.position || 0);
                            if (position > 0) {
                                label.style.gridColumn = `${((position - 1) % 3) + 1}`;
                                label.style.gridRow = `${Math.floor((position - 1) / 3) + 2}`;
                            }
                            map.append(label);
                        });
                        tables.insertBefore(map, tables.querySelector('.booking-table-legend'));
                    });
                    renderFloorTabs(floors);
                    filterTables();
                };
                const refreshAvailability = async () => {
                    if (!restaurant.value || !date.value || !time.value) return;
                    const query = new URLSearchParams({restaurant_id: restaurant.value, booking_date: date.value, booking_time: time.value});
                    try {
                        const response = await fetch(`{{ route('booking.availability') }}?${query}`, {headers: {'Accept': 'application/json'}});
                        if (!response.ok) return;
                        const data = await response.json();
                        if (renderedRestaurant !== restaurant.value) {
                            renderedRestaurant = restaurant.value;
                            renderRestaurantTables(data.tables || []);
                        }
                        const unavailable = new Set(data.unavailable || []);
                        tables.querySelectorAll('label.booking-table').forEach((label) => {
                            const input = label.querySelector('input');
                            const blocked = unavailable.has(input.value);
                            input.disabled = blocked;
                            if (blocked) input.checked = false;
                            label.classList.toggle('is-unavailable', blocked);
                            label.setAttribute('aria-label', `${input.value}: ${blocked ? '{{ $isEnglish ? 'booked' : 'đã đặt' }}' : '{{ $isEnglish ? 'available' : 'còn trống' }}'}`);
                            label.title = blocked ? '{{ $isEnglish ? 'Unavailable for this time' : 'Bàn đã được đặt vào giờ này' }}' : '';
                        });
                        updateSelectedCapacity();
                    } catch (_) {}
                };
                const filterTables = () => {
                    tables.querySelectorAll('[data-floor-map]').forEach((map) => { map.hidden = map.dataset.floorMap !== activeFloor; });
                    tables.querySelectorAll('[data-floor-tab]').forEach((tab) => tab.setAttribute('aria-selected', tab.dataset.floorTab === activeFloor ? 'true' : 'false'));

                    
                    tables.querySelectorAll('label.booking-table[data-position]').forEach((label) => {
                        const position = Number(label.dataset.position);
                        if (position > 0) {
                            label.style.gridColumn = `${((position - 1) % 3) + 1}`;
                            label.style.gridRow = `${Math.floor((position - 1) / 3) + 2}`;
                        }
                    });
                    refreshAvailability();
                };
                const updateSelectedCapacity = () => {
                    const selected = [...tables.querySelectorAll('input[name="table_codes[]"]:checked')];
                    const capacity = selected.reduce((sum, input) => sum + Number(input.closest('.booking-table').dataset.capacity || 0), 0);
                    const count = Number(guests.value || 0);
                    const summary = document.getElementById('bookingCapacity');
                    if (summary) summary.textContent = `{{ $isEnglish ? 'Selected seats: ' : 'Số chỗ đã chọn: ' }}${capacity} / ${count}`;
                    tables.querySelectorAll('.booking-table:not(.is-unavailable) input:not(:checked)').forEach((input) => {
                        input.disabled = capacity >= count;
                    });
                };
                tables.addEventListener('change', (event) => {
                    if (event.target.matches('input[name="table_codes[]"]')) updateSelectedCapacity();
                });
                restaurant.addEventListener('change', filterTables);
                [date, time].forEach((input) => input.addEventListener('change', filterTables));
                guests.addEventListener('change', () => {
                    updateSelectedCapacity();
                });
                filterTables();
                updateSelectedCapacity();
            })();
        </script>
    @endif
</body>
</html>






