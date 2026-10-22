<x-filament-panels::page>
    @php
        $cards = [
            ['label' => 'Tổng lượt đặt bàn', 'value' => $bookingsCount, 'note' => 'Tất cả thời gian', 'icon' => 'heroicon-o-calendar-days'],
            ['label' => 'Lịch đặt hôm nay', 'value' => $todayBookingsCount, 'note' => now()->locale('vi')->isoFormat('dddd, DD/MM'), 'icon' => 'heroicon-o-clock'],
            ['label' => 'Chờ xác nhận', 'value' => $pendingBookingsCount, 'note' => 'Cần được xử lý', 'icon' => 'heroicon-o-bell-alert'],
            ['label' => 'Yêu cầu hủy bàn', 'value' => $pendingCancellationCount, 'note' => 'Chờ admin xác nhận', 'icon' => 'heroicon-o-x-circle'],
            ['label' => 'Khách đăng ký mới', 'value' => $newLeadsCount, 'note' => 'Đang chờ liên hệ', 'icon' => 'heroicon-o-user-plus'],
        ];
        $maxCount = max(1, (int) $bookingTrend->max('count'));
    @endphp

    <style>
        .yk-dashboard { display:grid; gap:24px; color:#f5f5f4; }
        .yk-dashboard * { box-sizing:border-box; }
        .yk-dashboard a { text-decoration:none; }
        .yk-hero { position:relative; overflow:hidden; padding:32px; border:1px solid rgba(255,255,255,.08); border-radius:18px; background:radial-gradient(ellipse at 90% 0%,rgba(153,27,27,.5),transparent 44%),linear-gradient(125deg,#1c1917,#29211f 65%,#3f1718); }
        .yk-hero__eyebrow { margin:0 0 10px; color:#fbbf24; font-size:12px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; }
        .yk-hero h2 { margin:0; color:#fff; font-size:clamp(23px,3vw,32px); font-weight:650; letter-spacing:-.025em; }
        .yk-hero__copy { max-width:660px; margin:10px 0 20px; color:#d6d3d1; font-size:14px; line-height:1.65; }
        .yk-button { display:inline-flex; align-items:center; gap:9px; padding:10px 15px; border-radius:9px; background:#fafaf9; color:#1c1917!important; font-size:13px; font-weight:650; transition:background .15s; }
        .yk-button:hover { background:#fef3c7; }
        .yk-button svg { width:16px; height:16px; }
        .yk-cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:16px; }
        .yk-card,.yk-panel { min-width:0; border:1px solid rgba(255,255,255,.09); border-radius:15px; background:#18181b; box-shadow:0 8px 24px rgba(0,0,0,.12); }
        .yk-card { padding:20px; }
        .yk-card__top { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; }
        .yk-card__label { margin:0; color:#a1a1aa; font-size:13px; font-weight:500; }
        .yk-card__value { margin:12px 0 0; color:#fafafa; font-size:32px; font-weight:650; letter-spacing:-.04em; line-height:1; }
        .yk-card__note { margin:13px 0 0; color:#8b8b95; font-size:12px; }
        .yk-card__icon { display:grid; width:38px; height:38px; flex:0 0 38px; place-items:center; border:1px solid rgba(248,113,113,.17); border-radius:11px; background:rgba(127,29,29,.2); color:#fca5a5; }
        .yk-card__icon svg { width:19px; height:19px; }
        .yk-main-grid { display:grid; grid-template-columns:minmax(0,1.55fr) minmax(280px,1fr); gap:20px; }
        .yk-panel { overflow:hidden; padding:22px; }
        .yk-panel__head { display:flex; justify-content:space-between; align-items:flex-start; gap:14px; }
        .yk-panel__title { margin:0; color:#fafafa; font-size:16px; font-weight:650; }
        .yk-panel__subtitle { margin:5px 0 0; color:#a1a1aa; font-size:13px; }
        .yk-chip { padding:6px 10px; border:1px solid rgba(255,255,255,.08); border-radius:999px; color:#d4d4d8; font-size:11px; white-space:nowrap; }
        .yk-chart { display:grid; grid-template-columns:repeat(7,minmax(0,1fr)); align-items:end; gap:12px; margin-top:26px; }
        .yk-chart__day { display:flex; min-width:0; flex-direction:column; align-items:center; gap:8px; }
        .yk-chart__count { color:#e4e4e7; font-size:12px; font-weight:600; }
        .yk-chart__track { display:flex; width:100%; height:145px; align-items:flex-end; overflow:hidden; border:1px solid rgba(255,255,255,.035); border-radius:9px; background:#222226; }
        .yk-chart__bar { width:100%; min-height:5px; border-radius:8px 8px 3px 3px; background:linear-gradient(0deg,#991b1b,#ef4444); }
        .yk-chart__weekday { color:#d4d4d8; font-size:11px; }
        .yk-chart__date { color:#71717a; font-size:10px; }
        .yk-leads { margin-top:16px; }
        .yk-lead { display:flex; align-items:center; gap:12px; padding:13px 0; border-top:1px solid rgba(255,255,255,.07); }
        .yk-lead:first-child { border-top:0; }
        .yk-avatar { display:grid; width:38px; height:38px; flex:0 0 38px; place-items:center; border:1px solid rgba(251,191,36,.15); border-radius:50%; background:rgba(120,73,10,.2); color:#fcd34d; font-size:14px; font-weight:650; }
        .yk-lead__body { min-width:0; flex:1; }
        .yk-lead__name,.yk-lead__contact { overflow:hidden; margin:0; text-overflow:ellipsis; white-space:nowrap; }
        .yk-lead__name { color:#f4f4f5; font-size:13px; font-weight:550; }
        .yk-lead__contact { margin-top:4px; color:#a1a1aa; font-size:11px; }
        .yk-badge { padding:5px 9px; border:1px solid rgba(251,191,36,.13); border-radius:999px; background:rgba(120,73,10,.16); color:#fcd34d; font-size:10px; }
        .yk-link { color:#fca5a5!important; font-size:12px; font-weight:600; white-space:nowrap; }
        .yk-link:hover { color:#fecaca!important; }
        .yk-empty { padding:28px 0; color:#a1a1aa; font-size:13px; text-align:center; }
        .yk-table-wrap { overflow-x:auto; }
        .yk-table { width:100%; min-width:680px; border-collapse:collapse; text-align:left; }
        .yk-table th { padding:12px 16px; border-top:1px solid rgba(255,255,255,.07); border-bottom:1px solid rgba(255,255,255,.07); background:rgba(255,255,255,.025); color:#a1a1aa; font-size:10px; font-weight:600; letter-spacing:.08em; text-transform:uppercase; }
        .yk-table td { padding:14px 16px; border-bottom:1px solid rgba(255,255,255,.06); color:#d4d4d8; font-size:12px; white-space:nowrap; }
        .yk-table tbody tr:last-child td { border-bottom:0; }
        .yk-table__customer { color:#fafafa!important; font-weight:550; }
        .yk-table__phone { display:block; margin-top:4px; color:#8b8b95; font-size:11px; }
        .yk-status { display:inline-block; padding:5px 9px; border-radius:999px; font-size:10px; font-weight:550; }
        .yk-status--pending { background:rgba(120,73,10,.22); color:#fcd34d; }
        .yk-status--confirmed { background:rgba(30,64,175,.22); color:#93c5fd; }
        .yk-status--completed { background:rgba(20,83,45,.25); color:#86efac; }
        .yk-status--cancelled { background:rgba(255,255,255,.08); color:#a1a1aa; }
        @media(max-width:1100px) { .yk-cards { grid-template-columns:repeat(2,minmax(0,1fr)); } .yk-main-grid { grid-template-columns:1fr; } }
        @media(max-width:640px) { .yk-hero { padding:23px 20px; } .yk-cards { gap:10px; } .yk-card { padding:15px; } .yk-card__value { font-size:27px; } .yk-card__icon { width:32px; height:32px; flex-basis:32px; } .yk-panel { padding:17px; } .yk-chart { gap:6px; } .yk-chart__track { height:112px; } }
    </style>

    <div class="yk-dashboard">
        <section class="yk-hero">
            <p class="yk-hero__eyebrow">Yakiniku · Quản trị nhà hàng</p>
            <h2>Chào {{ auth()->user()?->name ?? 'bạn' }}, sẵn sàng cho hôm nay?</h2>
            <p class="yk-hero__copy">Theo dõi lịch đặt bàn, xử lý yêu cầu mới và nắm nhanh tình hình hoạt động của nhà hàng.</p>
            <a href="{{ \App\Filament\Resources\Bookings\BookingResource::getUrl('index') }}" class="yk-button">Xem lịch đặt bàn <x-filament::icon icon="heroicon-m-arrow-right" /></a>
        </section>

        <section class="yk-cards" aria-label="Tổng quan">
            @foreach ($cards as $card)
                <article class="yk-card">
                    <div class="yk-card__top">
                        <div><p class="yk-card__label">{{ $card['label'] }}</p><p class="yk-card__value">{{ number_format($card['value']) }}</p></div>
                        <span class="yk-card__icon"><x-filament::icon :icon="$card['icon']" /></span>
                    </div>
                    <p class="yk-card__note">{{ $card['note'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="yk-main-grid">
            <article class="yk-panel">
                <div class="yk-panel__head"><div><h3 class="yk-panel__title">Xu hướng đặt bàn</h3><p class="yk-panel__subtitle">Số lượt đặt trong 7 ngày gần nhất</p></div><span class="yk-chip">7 ngày</span></div>
                <div class="yk-chart" role="img" aria-label="Biểu đồ số lượt đặt bàn trong 7 ngày gần nhất">
                    @foreach ($bookingTrend as $day)
                        @php $height = max(5, (int) round(($day['count'] / $maxCount) * 100)); @endphp
                        <div class="yk-chart__day"><span class="yk-chart__count">{{ $day['count'] }}</span><div class="yk-chart__track"><div class="yk-chart__bar" style="height:{{ $height }}%"></div></div><span class="yk-chart__weekday">{{ $day['weekday'] }}</span><span class="yk-chart__date">{{ $day['label'] }}</span></div>
                    @endforeach
                </div>
            </article>

            <article class="yk-panel">
                <div class="yk-panel__head"><div><h3 class="yk-panel__title">Đăng ký ưu đãi mới</h3><p class="yk-panel__subtitle">Khách đang chờ liên hệ</p></div><a class="yk-link" href="{{ \App\Filament\Resources\Leads\LeadResource::getUrl('index') }}">Xem tất cả</a></div>
                <div class="yk-leads">
                    @forelse ($recentLeads as $lead)
                        <div class="yk-lead"><span class="yk-avatar">{{ mb_strtoupper(mb_substr($lead->name ?: 'K', 0, 1)) }}</span><div class="yk-lead__body"><p class="yk-lead__name">{{ $lead->name ?: 'Khách hàng' }}</p><p class="yk-lead__contact">{{ $lead->phone ?: $lead->email }}</p></div><span class="yk-badge">Mới</span></div>
                    @empty
                        <p class="yk-empty">Chưa có đăng ký mới.</p>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="yk-panel" style="padding:0">
            <div class="yk-panel__head" style="padding:21px 22px"><div><h3 class="yk-panel__title">Đặt bàn gần đây</h3><p class="yk-panel__subtitle">Các yêu cầu mới nhất từ khách hàng</p></div><a class="yk-link" href="{{ \App\Filament\Resources\Bookings\BookingResource::getUrl('index') }}">Quản lý đặt bàn →</a></div>
            <div class="yk-table-wrap"><table class="yk-table"><thead><tr><th>Khách hàng</th><th>Cơ sở</th><th>Ngày & giờ</th><th>Số khách</th><th>Trạng thái</th></tr></thead><tbody>
                @forelse ($recentBookings as $booking)
                    @php
                        [$statusLabel, $statusClass] = match ($booking->status) {
                            \App\Enums\BookingStatus::Pending->value => ['Chờ xác nhận', 'pending'],
                            \App\Enums\BookingStatus::Confirmed->value => ['Đã xác nhận', 'confirmed'],
                            \App\Enums\BookingStatus::CancellationRequested->value => ['Chờ duyệt hủy', 'pending'],
                            \App\Enums\BookingStatus::Completed->value => ['Hoàn thành', 'completed'],
                            default => ['Đã hủy', 'cancelled'],
                        };
                    @endphp
                    <tr><td class="yk-table__customer">{{ $booking->customer_name }}<span class="yk-table__phone">{{ $booking->phone }}</span></td><td>{{ $booking->restaurant?->name ?? '—' }}</td><td>{{ $booking->booking_date?->format('d/m/Y') }} · {{ $booking->booking_time }}</td><td>{{ $booking->number_of_guests }}</td><td><span class="yk-status yk-status--{{ $statusClass }}">{{ $statusLabel }}</span></td></tr>
                @empty
                    <tr><td colspan="5" class="yk-empty">Chưa có lịch đặt bàn.</td></tr>
                @endforelse
            </tbody></table></div>
        </section>
    </div>
</x-filament-panels::page>
