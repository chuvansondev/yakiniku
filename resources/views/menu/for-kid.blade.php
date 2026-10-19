@php
    $isEnglish = app()->getLocale() === 'en';
    $menuBrand = setting('header_brand_name'.($isEnglish ? '_en' : '')) ?: config('app.name');
    $queryFor = fn (array $changes = []) => route('menu.for-kid', array_filter(array_merge(request()->query(), $changes), fn ($value) => $value !== ''));
    $visibleCategories = $selectedType === '' || $selectedType === 'food'
        ? $categoryLabels
        : [];
@endphp
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $isEnglish ? 'Explore the kids menu with food and essentials for little guests at '.$menuBrand.'.' : 'Khám phá thực đơn trẻ em và các tiện ích dành cho thực khách nhí tại '.$menuBrand.'.' }}">
    <title>{{ $isEnglish ? 'For Kids' : 'Dành cho trẻ con' }} | {{ $menuBrand }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG_B.woff2') }}') format('woff2');font-weight:700;font-display:swap}
        @font-face{font-family:'Dense';src:url('{{ asset('fonts/Dense.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        .kids-page{--kids-red:#a42127;--kids-ink:#332a26;--kids-muted:#75645a;min-height:100vh;background:#fbf9f6;color:var(--kids-ink);font-family:'UTM BryantLG',Arial,sans-serif}
        .kids-page *{box-sizing:border-box}.kids-hero{position:relative;display:grid;min-height:235px;place-items:center;overflow:hidden;background:linear-gradient(120deg,#241714,#493027 52%,#231a17);color:#fff;text-align:center}
        .kids-hero::before{position:absolute;inset:0;opacity:.25;background:radial-gradient(ellipse at 50% 110%,#ba7b4a 0,transparent 55%),repeating-linear-gradient(135deg,transparent 0 8px,rgba(255,255,255,.035) 8px 9px);content:''}.kids-hero::after{position:absolute;inset:18px;border:1px solid rgba(230,200,161,.3);content:''}
        .kids-hero__content{position:relative;padding:34px 20px}.kids-hero__eyebrow{margin:0 0 10px;color:#dfbd96;font-size:12px;font-weight:700;letter-spacing:.28em;text-transform:uppercase}
        .kids-hero h1{margin:0;color:#fff;font-family:'UTM Pacific','Dense',serif;font-size:clamp(38px,6.5vw,64px);font-weight:400;letter-spacing:.035em;line-height:1.1}.kids-hero__sub{margin:14px 0 0;color:#e7d4c0;font-size:16px}
        .kids-wrap{width:min(1180px,calc(100% - 48px));margin:auto;padding:38px 0 72px}.kids-tabs{display:flex;justify-content:center;gap:9px;margin:0 0 28px;overflow:auto}.kids-tabs a{flex:0 0 auto;padding:10px 16px;border:1px solid #e5d9ce;color:#68574d;font-size:13px;font-weight:700;text-decoration:none;transition:all .18s}.kids-tabs a:hover,.kids-tabs a.is-active{border-color:var(--kids-red);background:var(--kids-red);color:#fff}
        .kids-filters{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:8px;margin:0 auto 34px}.kids-filters__label{margin:0 5px;color:#9b8068;font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase}.kids-filters a{padding:8px 12px;border:1px solid #e8dfd7;background:#fff;color:#68574d;font-size:13px;text-decoration:none}.kids-filters a.is-active,.kids-filters a:hover{border-color:#b48b63;color:var(--kids-red)}
        .kids-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:22px}.kids-card{overflow:hidden;border:1px solid #e8dfd7;background:#fff;box-shadow:0 9px 24px rgba(58,39,28,.05)}.kids-card__image{display:block;width:100%;aspect-ratio:1.48;object-fit:cover;background:linear-gradient(135deg,#44342c,#a07a5f)}.kids-card__body{padding:18px 19px 20px}.kids-card__title{margin:0;color:var(--kids-red);font-family:'UTM Pacific','Dense',serif;font-size:22px;font-weight:400;line-height:1.25}.kids-card__description{margin:9px 0 15px;color:var(--kids-muted);font-size:14px;line-height:1.55}.kids-card__meta{display:flex;align-items:center;justify-content:space-between;gap:12px;padding-top:12px;border-top:1px solid #eee7e1}.kids-card__category{color:#927e6c;font-size:12px}.kids-card__price{color:var(--kids-red);font-size:16px;font-weight:700;white-space:nowrap}
        .kids-empty{grid-column:1/-1;padding:55px 22px;border:1px solid #e8dfd7;background:#fff;text-align:center}.kids-empty h2{margin:0 0 8px;color:var(--kids-red);font-family:'UTM Pacific','Dense',serif;font-size:28px;font-weight:400}.kids-empty p{margin:0;color:var(--kids-muted);font-size:15px}
        .kids-cta{padding:43px 22px;background:#38271f;color:#fff;text-align:center}.kids-cta p{margin:0 0 16px;color:#dfbd96;font-family:'UTM Pacific','Dense',serif;font-size:27px}.kids-cta a{display:inline-flex;padding:12px 21px;background:#f7f0e8;color:#6d3128;font-size:13px;font-weight:700;letter-spacing:.07em;text-decoration:none}
        @media(max-width:900px){.kids-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:17px}}@media(max-width:600px){.kids-hero{min-height:195px}.kids-wrap{width:calc(100% - 32px);padding:26px 0 48px}.kids-grid{grid-template-columns:1fr}.kids-card__image{aspect-ratio:1.6}.kids-filters{justify-content:flex-start}.kids-tabs{justify-content:flex-start;margin-bottom:22px}.kids-card__description{font-size:15px}}
    </style>
</head>
<body>
    @include('frontend.partials.header')
    <main class="kids-page">
        <section class="kids-hero">
            <div class="kids-hero__content">
                <p class="kids-hero__eyebrow">{{ $menuBrand }}</p>
                <h1>{{ $isEnglish ? 'For Kids' : 'Dành cho trẻ con' }}</h1>
                <p class="kids-hero__sub">{{ $isEnglish ? 'A little something for our little guests' : 'Thực đơn và tiện ích dành riêng cho thực khách nhí' }}</p>
            </div>
        </section>
        <div class="kids-wrap">
            <nav class="kids-tabs" aria-label="{{ $isEnglish ? 'Menu pages' : 'Các trang thực đơn' }}">
                <a href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'Our Menu' : 'Thực đơn' }}</a>
                <a href="{{ route('menu.must-try') }}">Must Try</a>
                <a href="{{ route('menu.combos') }}">Combo</a>
                <a href="{{ route('menu.promotions') }}">{{ $isEnglish ? 'Promotions' : 'Khuyến mãi' }}</a>
                <a href="{{ route('menu.for-kid') }}" class="is-active">{{ $isEnglish ? 'For Kids' : 'Dành cho trẻ con' }}</a>
            </nav>

            <nav class="kids-filters" aria-label="{{ $isEnglish ? 'Filter kids menu' : 'Lọc thực đơn trẻ em' }}">
                <span class="kids-filters__label">{{ $isEnglish ? 'Type' : 'Nhóm' }}</span>
                <a href="{{ $queryFor(['type' => '', 'category' => '']) }}" class="{{ $selectedType === '' ? 'is-active' : '' }}">{{ $isEnglish ? 'All' : 'Tất cả' }}</a>
                @foreach ($typeLabels as $type => $label)
                    <a href="{{ $queryFor(['type' => $type, 'category' => '']) }}" class="{{ $selectedType === $type ? 'is-active' : '' }}">{{ $label }}</a>
                @endforeach
                @if ($visibleCategories)
                    <span class="kids-filters__label">{{ $isEnglish ? 'Food' : 'Món ăn' }}</span>
                    @foreach ($visibleCategories as $category => $label)
                        <a href="{{ $queryFor(['type' => $selectedType, 'category' => $selectedCategory === $category ? '' : $category]) }}" class="{{ $selectedCategory === $category ? 'is-active' : '' }}">{{ $label }}</a>
                    @endforeach
                @endif
            </nav>

            <div class="kids-grid">
                @forelse ($items as $item)
                    @php
                        $itemName = $isEnglish ? ($item->name_en ?: $item->name) : $item->name;
                        $itemDescription = $isEnglish ? ($item->description_en ?: $item->description) : $item->description;
                    @endphp
                    <article class="kids-card">
                        @if ($item->menu_image_url)
                            <img class="kids-card__image" src="{{ $item->menu_image_url }}" alt="{{ $itemName }}" loading="lazy">
                        @else
                            <div class="kids-card__image" role="img" aria-label="{{ $itemName }}"></div>
                        @endif
                        <div class="kids-card__body">
                            <h2 class="kids-card__title">{{ $itemName }}</h2>
                            @if (filled($itemDescription))<p class="kids-card__description">{{ $itemDescription }}</p>@endif
                            <div class="kids-card__meta">
                                <span class="kids-card__category">{{ $categoryLabels[$item->food_category] ?? ($typeLabels[$item->type] ?? $item->type) }}</span>
                                <span class="kids-card__price">{{ localized_price($item->price) }}</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="kids-empty">
                        <h2>{{ $isEnglish ? 'Kids menu coming soon' : 'Thực đơn trẻ em sẽ sớm được cập nhật' }}</h2>
                        <p>{{ $isEnglish ? 'Please check back soon. Our team will be happy to help you choose.' : 'Vui lòng quay lại sau. Nhân viên nhà hàng luôn sẵn lòng hỗ trợ gia đình.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        <section class="kids-cta">
            <p>{{ $isEnglish ? 'A happy meal for the whole family' : 'Cả gia đình cùng vui bên bàn ăn' }}</p>
            <a href="{{ url('/booking') }}">{{ $isEnglish ? 'BOOK A TABLE' : 'ĐẶT BÀN' }}</a>
        </section>
    </main>
    @include('frontend.partials.footer')
</body>
</html>
