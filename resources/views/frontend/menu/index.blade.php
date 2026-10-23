@php
    $isEnglish = app()->getLocale() === 'en';
    $menuBrand = setting('header_brand_name'.($isEnglish ? '_en' : '')) ?: config('app.name');
@endphp
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    @include('frontend.partials.assets')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $mustTryOnly ? ($isEnglish ? 'Explore the signature must-try dishes at '.$menuBrand.'.' : 'Khám phá những món đặc sắc nhất định nên thử tại '.$menuBrand.'.') : ($isEnglish ? 'Explore the Yakiniku menu, seasonal specialties and signature cuts.' : 'Khám phá thực đơn Yakiniku với những món đặc sắc và tuyển chọn.') }}">
    <title>{{ $mustTryOnly ? 'Must Try' : ($isEnglish ? 'Our Menu' : 'Thực đơn') }} | {{ $menuBrand }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-style:normal;font-weight:400;font-display:swap}
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG_B.woff2') }}') format('woff2');font-style:normal;font-weight:700;font-display:swap}
        @font-face{font-family:'Dense';src:url('{{ asset('fonts/Dense.woff2') }}') format('woff2');font-style:normal;font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-style:normal;font-weight:400;font-display:swap}
        .menu-page{--menu-red:#a42127;--menu-ink:#332a26;--menu-muted:#75645a;min-height:100vh;background:#fbf9f6;color:var(--menu-ink);font-family:'UTM BryantLG',Arial,sans-serif}
        .menu-page *{box-sizing:border-box}.menu-page a{color:inherit}
        .menu-hero{position:relative;display:grid;min-height:235px;place-items:center;overflow:hidden;background:linear-gradient(120deg,#241714,#493027 52%,#231a17);color:#fff;text-align:center}
        .menu-hero::before,.menu-hero::after{position:absolute;inset:0;content:'';pointer-events:none}
        .menu-hero::before{opacity:.24;background:radial-gradient(ellipse at 50% 110%,#ba7b4a 0,transparent 55%),repeating-linear-gradient(135deg,transparent 0 8px,rgba(255,255,255,.035) 8px 9px)}
        .menu-hero::after{inset:18px;border:1px solid rgba(230,200,161,.3)}
        .menu-hero__content{position:relative;z-index:1;padding:34px 20px}
        .menu-hero__eyebrow{margin:0 0 10px;color:#dfbd96;font-size:12px;font-weight:700;letter-spacing:.28em;text-transform:uppercase}
        .menu-hero h1{margin:0;color:#fff;font-family:'UTM Pacific','Dense',serif;font-size:clamp(42px,7vw,68px);font-weight:400;letter-spacing:.045em;line-height:1.05}
        .menu-hero__rule{display:flex;align-items:center;justify-content:center;gap:10px;margin:18px auto 0;color:#d9b384;font-size:12px;letter-spacing:.28em;text-transform:uppercase}
        .menu-hero__rule::before,.menu-hero__rule::after{width:36px;height:1px;background:#d9b384;content:''}
        .menu-layout{width:min(1280px,calc(100% - 48px));margin:0 auto;padding:36px 0 70px}
        .menu-jump{position:sticky;top:0;z-index:10;margin:0 -24px 44px;padding:13px 24px;background:rgba(251,249,246,.96);border-bottom:1px solid #e7ddd4;box-shadow:0 6px 18px rgba(52,34,24,.055);backdrop-filter:blur(12px)}
        .menu-jump__inner{display:flex;align-items:center;gap:8px;max-width:1280px;margin:auto;overflow-x:auto;scrollbar-width:thin;scrollbar-color:#c7aa8a transparent}
        .menu-jump__label{flex:0 0 auto;margin-right:8px;color:#9b8068;font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase}
        .menu-jump a{flex:0 0 auto;padding:9px 13px;border:1px solid #e5d9ce;color:#68574d;font-size:13px;font-weight:700;letter-spacing:.025em;text-decoration:none;transition:background .18s,color .18s,border-color .18s}
        .menu-jump a:hover,.menu-jump a:focus{border-color:var(--menu-red);background:var(--menu-red);color:#fff}
        .menu-section{scroll-margin-top:94px;margin:0 0 62px}
        .menu-section__heading{display:grid;grid-template-columns:180px minmax(0,1fr);align-items:center;gap:30px;margin-bottom:27px}
        .menu-section__cover{width:180px;height:116px;object-fit:cover;background:#e9e0d8}
        .menu-section__cover--empty{display:grid;place-items:center;background:linear-gradient(135deg,#45342b,#9c785e);color:#ead8c4;font-family:Georgia,serif;font-size:38px}
        .menu-section__title{margin:0;color:var(--menu-red);font-family:'UTM Pacific','Dense',serif;font-size:clamp(24px,3vw,35px);font-weight:400;letter-spacing:.035em;line-height:1.15;text-transform:uppercase}
        .menu-section__description{max-width:670px;margin:9px 0 0;color:var(--menu-muted);font-size:16px;line-height:1.55}
        .menu-items{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));column-gap:42px;row-gap:0}
        .menu-item{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:5px 18px;align-content:start;padding:16px 0 15px;border-top:1px solid #e9e2dc}
        .menu-item__name{margin:0;font-size:17px;font-weight:700;line-height:1.35}
        .menu-item__price{align-self:start;color:var(--menu-red);font-size:16px;font-weight:700;white-space:nowrap}
        .menu-item__description{grid-column:1/-1;max-width:570px;margin:0;color:#87786f;font-size:14px;line-height:1.45}
        .menu-item__badge{display:inline-block;margin-left:7px;padding:3px 6px;background:#f3e9dd;color:#94653a;font-size:10px;font-weight:700;letter-spacing:.08em;vertical-align:2px;text-transform:uppercase}
        .menu-note{margin:8px 0 0;padding:15px 18px;border-left:2px solid #b48b63;background:#f3eee8;color:#75665c;font-size:14px;line-height:1.6}
        .menu-empty{padding:55px 20px;background:#fff;text-align:center;color:var(--menu-muted)}
        .menu-cta{position:relative;overflow:hidden;padding:47px 22px;background:#38271f;color:#fff;text-align:center}
        .menu-cta::before{position:absolute;inset:12px;border:1px solid rgba(223,189,150,.28);content:'';pointer-events:none}
        .menu-cta__content{position:relative}.menu-cta p{margin:0 0 7px;color:#dfbd96;font-size:12px;font-weight:700;letter-spacing:.2em;text-transform:uppercase}
        .menu-cta h2{margin:0 0 20px;font-family:'UTM Pacific','Dense',serif;font-size:clamp(25px,4vw,35px);font-weight:400}
        .menu-cta a{display:inline-flex;min-width:170px;justify-content:center;padding:13px 20px;background:#f7f0e8;color:#6d3128;text-decoration:none;font-size:13px;font-weight:700;letter-spacing:.08em;transition:background .18s}
        .menu-cta a:hover{background:#fff}
        @media(max-width:700px){.menu-hero{min-height:195px}.menu-layout{width:calc(100% - 32px);padding-top:24px}.menu-jump{margin:0 -16px 34px;padding:11px 16px}.menu-jump__label{display:none}.menu-section{scroll-margin-top:70px;margin-bottom:46px}.menu-section__heading{grid-template-columns:112px minmax(0,1fr);gap:17px;margin-bottom:16px}.menu-section__cover{width:112px;height:94px}.menu-section__description{font-size:14px}.menu-items{grid-template-columns:1fr;column-gap:0}.menu-item{padding:14px 0}.menu-item__name{font-size:16px}.menu-item__price{font-size:15px}}
        @media(prefers-reduced-motion:reduce){.menu-page *{scroll-behavior:auto!important;transition:none!important}}
        html{scroll-behavior:smooth}
    </style>
</head>
<body>
    @include('frontend.partials.header')
    <main class="menu-page">
        <section class="menu-hero" aria-labelledby="menu-title">
            <div class="menu-hero__content">
                <p class="menu-hero__eyebrow">{{ $menuBrand }}</p>
                <h1 id="menu-title">{{ $mustTryOnly ? 'Must Try' : ($isEnglish ? 'Our Menu' : 'Thực đơn') }}</h1>
                <div class="menu-hero__rule">{{ $mustTryOnly ? ($isEnglish ? 'Our signature dishes' : 'Món ngon nhất định phải thử') : ($isEnglish ? 'A taste worth sharing' : 'Tinh hoa vị Nhật') }}</div>
            </div>
        </section>

        <div class="menu-layout">
            @if ($categories->isNotEmpty())
                <nav class="menu-jump" aria-label="{{ $isEnglish ? 'Quick menu navigation' : 'Chọn nhanh danh mục thực đơn' }}">
                    <div class="menu-jump__inner">
                        <span class="menu-jump__label">{{ $isEnglish ? 'Explore' : 'Chọn nhanh' }}</span>
                        <a href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'Our Menu' : 'Toàn bộ thực đơn' }}</a>
                        <a href="{{ route('menu.must-try') }}">Must Try</a>
                        <span class="menu-jump__label">{{ $isEnglish ? 'Categories' : 'Danh mục' }}</span>
                        @foreach ($categories as $category)
                            <a href="#menu-category-{{ $category->id }}">{{ $isEnglish ? ($category->name_en ?: $category->name) : $category->name }}</a>
                        @endforeach
                    </div>
                </nav>

                @foreach ($categories as $category)
                    @php
                        $categoryName = $isEnglish ? ($category->name_en ?: $category->name) : $category->name;
                        $categoryDescription = $isEnglish ? ($category->description_en ?: $category->description) : $category->description;
                    @endphp
                    <section class="menu-section" id="menu-category-{{ $category->id }}" aria-labelledby="menu-category-title-{{ $category->id }}">
                        <div class="menu-section__heading">
                            @if ($category->menu_image_url)
                                <img class="menu-section__cover" src="{{ $category->menu_image_url }}" alt="{{ $categoryName }}" loading="lazy">
                            @else
                                <div class="menu-section__cover menu-section__cover--empty" aria-hidden="true">{{ mb_substr($categoryName, 0, 1) }}</div>
                            @endif
                            <div>
                                <h2 class="menu-section__title" id="menu-category-title-{{ $category->id }}">{{ $categoryName }}</h2>
                                @if (filled($categoryDescription))<p class="menu-section__description">{{ $categoryDescription }}</p>@endif
                            </div>
                        </div>

                        <div class="menu-items">
                            @foreach ($category->menuItems as $item)
                                @php
                                    $itemName = $isEnglish ? ($item->name_en ?: $item->name) : $item->name;
                                    $itemDescription = $isEnglish ? ($item->description_en ?: $item->description) : $item->description;
                                @endphp
                                <article class="menu-item">
                                    <h3 class="menu-item__name">{{ $itemName }}@if($item->is_must_try)<span class="menu-item__badge">{{ $isEnglish ? 'Signature' : 'Nên thử' }}</span>@endif</h3>
                                    <span class="menu-item__price">{{ localized_price($item->price) }}</span>
                                    @if (filled($itemDescription))<p class="menu-item__description">{{ $itemDescription }}</p>@endif
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
                <p class="menu-note">{{ $mustTryOnly ? ($isEnglish ? 'A handpicked collection of signature dishes selected by our kitchen.' : 'Tuyển chọn những món đặc sắc được nhà bếp đề xuất.') : ($isEnglish ? 'Prices are listed in VND and may not include VAT. Please ask our team about seasonal availability.' : 'Giá các món được tính theo VND và chưa bao gồm VAT. Vui lòng liên hệ nhà hàng để biết thêm về các món theo mùa.') }}</p>
            @else
                <div class="menu-empty">{{ $mustTryOnly ? ($isEnglish ? 'Our signature selection is being updated. Please check back soon.' : 'Danh sách món Must Try đang được cập nhật. Vui lòng quay lại sau.') : ($isEnglish ? 'Our menu is being updated. Please check back soon.' : 'Thực đơn đang được cập nhật. Vui lòng quay lại sau.') }}</div>
            @endif
        </div>

        <section class="menu-cta">
            <div class="menu-cta__content">
                <p>{{ $isEnglish ? 'An exceptional meal awaits' : 'Một trải nghiệm ẩm thực đang chờ bạn' }}</p>
                <h2>{{ $isEnglish ? 'Reserve your table today' : 'Đặt bàn ngay hôm nay' }}</h2>
                <a href="{{ url('/booking') }}">{{ $isEnglish ? 'BOOK NOW' : 'ĐẶT BÀN' }}</a>
            </div>
        </section>
    </main>
    @include('frontend.partials.footer')
</body>
</html>
