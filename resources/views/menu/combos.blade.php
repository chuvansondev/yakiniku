@php
    $isEnglish = app()->getLocale() === 'en';
    $menuBrand = setting('header_brand_name'.($isEnglish ? '_en' : '')) ?: config('app.name');
@endphp
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $isEnglish ? 'Explore set menus and dining combos at '.$menuBrand.'.' : 'Khám phá các combo và set menu tại '.$menuBrand.'.' }}">
    <title>{{ $isEnglish ? 'Combos' : 'Combo' }} | {{ $menuBrand }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG_B.woff2') }}') format('woff2');font-weight:700;font-display:swap}
        @font-face{font-family:'Dense';src:url('{{ asset('fonts/Dense.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        .combo-page{--combo-red:#a42127;--combo-ink:#332a26;--combo-muted:#75645a;min-height:100vh;background:#fbf9f6;color:var(--combo-ink);font-family:'UTM BryantLG',Arial,sans-serif}
        .combo-page *{box-sizing:border-box}.combo-hero{position:relative;display:grid;min-height:235px;place-items:center;overflow:hidden;background:linear-gradient(120deg,#241714,#493027 52%,#231a17);color:#fff;text-align:center}
        .combo-hero::before{position:absolute;inset:0;opacity:.24;background:radial-gradient(ellipse at 50% 110%,#ba7b4a 0,transparent 55%),repeating-linear-gradient(135deg,transparent 0 8px,rgba(255,255,255,.035) 8px 9px);content:''}
        .combo-hero::after{position:absolute;inset:18px;border:1px solid rgba(230,200,161,.3);content:''}
        .combo-hero__content{position:relative;padding:34px 20px}.combo-hero__eyebrow{margin:0 0 10px;color:#dfbd96;font-size:12px;font-weight:700;letter-spacing:.28em;text-transform:uppercase}
        .combo-hero h1{margin:0;color:#fff;font-family:'UTM Pacific','Dense',serif;font-size:clamp(42px,7vw,68px);font-weight:400;letter-spacing:.045em;line-height:1.05}
        .combo-hero__sub{margin:15px 0 0;color:#e7d4c0;font-size:16px;letter-spacing:.04em}
        .combo-wrap{width:min(1200px,calc(100% - 48px));margin:auto;padding:48px 0 74px}
        .combo-tabs{display:flex;justify-content:center;gap:10px;margin:0 0 38px}.combo-tabs a{padding:10px 17px;border:1px solid #e5d9ce;color:#68574d;font-size:13px;font-weight:700;text-decoration:none;transition:all .18s}.combo-tabs a:hover,.combo-tabs a.is-active{border-color:var(--combo-red);background:var(--combo-red);color:#fff}
        .combo-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:26px}
        .combo-card{overflow:hidden;border:1px solid #e8dfd7;background:#fff;box-shadow:0 10px 28px rgba(58,39,28,.06)}
        .combo-card__image{position:relative;display:block;width:100%;aspect-ratio:1.45;object-fit:cover;background:linear-gradient(135deg,#44342c,#a07a5f)}
        .combo-card__fallback{display:grid;place-items:center;color:#ead8c4;font-family:Georgia,serif;font-size:64px}
        .combo-card__body{padding:22px 23px 24px}.combo-card__name{margin:0;color:var(--combo-red);font-family:'UTM Pacific','Dense',serif;font-size:25px;font-weight:400;line-height:1.2}
        .combo-card__description{min-height:48px;margin:10px 0 17px;color:var(--combo-muted);font-size:15px;line-height:1.55}
        .combo-card__items{display:grid;gap:8px;margin:0 0 18px;padding:15px 0;border-top:1px solid #eee7e1;border-bottom:1px solid #eee7e1;list-style:none}
        .combo-card__item{display:flex;justify-content:space-between;gap:12px;color:#5d5048;font-size:14px;line-height:1.4}.combo-card__quantity{flex:0 0 auto;color:#968476}
        .combo-card__price-row{display:flex;align-items:baseline;gap:10px}.combo-card__price{color:var(--combo-red);font-size:21px;font-weight:700}.combo-card__original{color:#968a82;font-size:15px;text-decoration:line-through}
        .combo-card__dates{margin:8px 0 0;color:#95877d;font-size:13px}
        .combo-empty{grid-column:1/-1;padding:58px 24px;border:1px solid #e8dfd7;background:#fff;text-align:center}.combo-empty h2{margin:0 0 9px;color:var(--combo-red);font-family:'UTM Pacific','Dense',serif;font-size:29px;font-weight:400}.combo-empty p{margin:0;color:var(--combo-muted);font-size:16px}
        .combo-cta{padding:46px 22px;background:#38271f;color:#fff;text-align:center}.combo-cta p{margin:0 0 17px;color:#dfbd96;font-family:'UTM Pacific','Dense',serif;font-size:28px}.combo-cta a{display:inline-flex;justify-content:center;padding:13px 22px;background:#f7f0e8;color:#6d3128;font-size:13px;font-weight:700;letter-spacing:.08em;text-decoration:none}
        @media(max-width:900px){.combo-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.combo-card__body{padding:19px}}
        @media(max-width:600px){.combo-hero{min-height:195px}.combo-wrap{width:calc(100% - 32px);padding:30px 0 50px}.combo-grid{grid-template-columns:1fr}.combo-card__image{aspect-ratio:1.6}.combo-card__description{min-height:0}.combo-tabs{gap:7px;margin-bottom:25px}.combo-tabs a{padding:9px 12px;font-size:12px}}
    </style>
</head>
<body>
    @include('frontend.partials.header')
    <main class="combo-page">
        <section class="combo-hero">
            <div class="combo-hero__content">
                <p class="combo-hero__eyebrow">{{ $menuBrand }}</p>
                <h1>Combo</h1>
                <p class="combo-hero__sub">{{ $isEnglish ? 'A little more to enjoy, together' : 'Tuyển chọn trọn vị, cùng nhau thưởng thức' }}</p>
            </div>
        </section>
        <div class="combo-wrap">
            <nav class="combo-tabs" aria-label="{{ $isEnglish ? 'Menu pages' : 'Các trang thực đơn' }}">
                <a href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'Our Menu' : 'Thực đơn' }}</a>
                <a href="{{ route('menu.must-try') }}">Must Try</a>
                <a href="{{ route('menu.combos') }}" class="is-active">Combo</a>
            </nav>
            <div class="combo-grid">
                @forelse ($combos as $combo)
                    @php
                        $comboName = $isEnglish ? ($combo->name_en ?: $combo->name) : $combo->name;
                        $comboDescription = $isEnglish ? ($combo->description_en ?: $combo->description) : $combo->description;
                    @endphp
                    <article class="combo-card">
                        @if ($combo->menu_image_url)
                            <img class="combo-card__image" src="{{ $combo->menu_image_url }}" alt="{{ $comboName }}" loading="lazy">
                        @else
                            <div class="combo-card__image combo-card__fallback" role="img" aria-label="{{ $comboName }}">{{ mb_substr($comboName, 0, 1) }}</div>
                        @endif
                        <div class="combo-card__body">
                            <h2 class="combo-card__name">{{ $comboName }}</h2>
                            @if (filled($comboDescription))<p class="combo-card__description">{{ $comboDescription }}</p>@else<p class="combo-card__description">{{ $isEnglish ? 'A thoughtfully prepared selection from our kitchen.' : 'Tuyển chọn món ngon được chuẩn bị từ căn bếp của chúng tôi.' }}</p>@endif
                            @if ($combo->menuItems->isNotEmpty())
                                <ul class="combo-card__items">
                                    @foreach ($combo->menuItems as $item)
                                        <li class="combo-card__item">
                                            <span>{{ $isEnglish ? ($item->name_en ?: $item->name) : $item->name }}</span>
                                            <span class="combo-card__quantity">× {{ $item->pivot->quantity }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <div class="combo-card__price-row">
                                <span class="combo-card__price">{{ localized_price($combo->price) }}</span>
                                @if ($combo->original_price && (float) $combo->original_price > (float) $combo->price)
                                    <span class="combo-card__original">{{ localized_price($combo->original_price) }}</span>
                                @endif
                            </div>
                            @if ($combo->start_date || $combo->end_date)
                                <p class="combo-card__dates">{{ $isEnglish ? 'Available' : 'Áp dụng' }}: {{ $combo->start_date?->format('d/m/Y') ?? '—' }} – {{ $combo->end_date?->format('d/m/Y') ?? '—' }}</p>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="combo-empty">
                        <h2>{{ $isEnglish ? 'Combos are coming soon' : 'Combo sẽ sớm được cập nhật' }}</h2>
                        <p>{{ $isEnglish ? 'Please check back soon or explore our full menu.' : 'Vui lòng quay lại sau hoặc khám phá thực đơn đầy đủ của chúng tôi.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        <section class="combo-cta">
            <p>{{ $isEnglish ? 'Find your next favorite' : 'Khám phá thêm hương vị yêu thích' }}</p>
            <a href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'VIEW OUR MENU' : 'XEM THỰC ĐƠN' }}</a>
        </section>
    </main>
    @include('frontend.partials.footer')
</body>
</html>
