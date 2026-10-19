@php
    $isEnglish = app()->getLocale() === 'en';
    $menuBrand = setting('header_brand_name'.($isEnglish ? '_en' : '')) ?: config('app.name');
@endphp
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $isEnglish ? 'Discover the latest dining offers and promotions at '.$menuBrand.'.' : 'Khám phá những ưu đãi và khuyến mãi mới nhất tại '.$menuBrand.'.' }}">
    <title>{{ $isEnglish ? 'Promotions' : 'Khuyến mãi' }} | {{ $menuBrand }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG_B.woff2') }}') format('woff2');font-weight:700;font-display:swap}
        @font-face{font-family:'Dense';src:url('{{ asset('fonts/Dense.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        .promo-page{--promo-red:#a42127;--promo-ink:#332a26;--promo-muted:#75645a;min-height:100vh;background:#fbf9f6;color:var(--promo-ink);font-family:'UTM BryantLG',Arial,sans-serif}
        .promo-page *{box-sizing:border-box}.promo-hero{position:relative;display:grid;min-height:235px;place-items:center;overflow:hidden;background:linear-gradient(120deg,#241714,#493027 52%,#231a17);color:#fff;text-align:center}
        .promo-hero::before{position:absolute;inset:0;opacity:.24;background:radial-gradient(ellipse at 50% 110%,#ba7b4a 0,transparent 55%),repeating-linear-gradient(135deg,transparent 0 8px,rgba(255,255,255,.035) 8px 9px);content:''}
        .promo-hero::after{position:absolute;inset:18px;border:1px solid rgba(230,200,161,.3);content:''}
        .promo-hero__content{position:relative;padding:34px 20px}.promo-hero__eyebrow{margin:0 0 10px;color:#dfbd96;font-size:12px;font-weight:700;letter-spacing:.28em;text-transform:uppercase}
        .promo-hero h1{margin:0;color:#fff;font-family:'UTM Pacific','Dense',serif;font-size:clamp(38px,6.5vw,64px);font-weight:400;letter-spacing:.035em;line-height:1.1}
        .promo-hero__sub{margin:14px 0 0;color:#e7d4c0;font-size:16px;letter-spacing:.035em}
        .promo-wrap{width:min(1180px,calc(100% - 48px));margin:auto;padding:46px 0 74px}
        .promo-tabs{display:flex;justify-content:center;gap:10px;margin:0 0 38px}.promo-tabs a{padding:10px 17px;border:1px solid #e5d9ce;color:#68574d;font-size:13px;font-weight:700;text-decoration:none;transition:all .18s}.promo-tabs a:hover,.promo-tabs a.is-active{border-color:var(--promo-red);background:var(--promo-red);color:#fff}
        .promo-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:27px}
        .promo-card{display:grid;grid-template-columns:minmax(180px,.86fr) minmax(0,1.14fr);min-height:235px;overflow:hidden;border:1px solid #e8dfd7;background:#fff;box-shadow:0 10px 28px rgba(58,39,28,.06)}
        .promo-card__image{width:100%;height:100%;min-height:235px;object-fit:cover;background:linear-gradient(135deg,#44342c,#a07a5f)}
        .promo-card__fallback{display:grid;place-items:center;color:#ead8c4;font-family:Georgia,serif;font-size:56px}
        .promo-card__body{display:flex;flex-direction:column;align-items:flex-start;padding:22px 21px}
        .promo-card__badge{margin:0 0 10px;color:#a17c59;font-size:11px;font-weight:700;letter-spacing:.16em;text-transform:uppercase}
        .promo-card__title{margin:0;color:var(--promo-red);font-family:'UTM Pacific','Dense',serif;font-size:23px;font-weight:400;line-height:1.25}
        .promo-card__summary{margin:11px 0 0;color:var(--promo-muted);font-size:15px;line-height:1.55}
        .promo-card__description{margin:10px 0 0;color:#786b62;font-size:14px;line-height:1.55;white-space:pre-line}
        .promo-card__dates{margin:auto 0 0;padding-top:19px;color:#928278;font-size:13px;line-height:1.45}
        .promo-empty{grid-column:1/-1;padding:58px 24px;border:1px solid #e8dfd7;background:#fff;text-align:center}.promo-empty h2{margin:0 0 9px;color:var(--promo-red);font-family:'UTM Pacific','Dense',serif;font-size:29px;font-weight:400}.promo-empty p{margin:0;color:var(--promo-muted);font-size:16px}
        .promo-cta{padding:46px 22px;background:#38271f;color:#fff;text-align:center}.promo-cta p{margin:0 0 17px;color:#dfbd96;font-family:'UTM Pacific','Dense',serif;font-size:28px}.promo-cta a{display:inline-flex;justify-content:center;padding:13px 22px;background:#f7f0e8;color:#6d3128;font-size:13px;font-weight:700;letter-spacing:.08em;text-decoration:none}
        @media(max-width:900px){.promo-grid{grid-template-columns:1fr;gap:18px}.promo-card{grid-template-columns:minmax(190px,.8fr) minmax(0,1.2fr)}}
        @media(max-width:560px){.promo-hero{min-height:195px}.promo-wrap{width:calc(100% - 32px);padding:30px 0 50px}.promo-card{grid-template-columns:1fr}.promo-card__image{height:auto;min-height:0;aspect-ratio:1.65}.promo-card__body{min-height:200px;padding:19px}.promo-tabs{gap:7px;margin-bottom:25px}.promo-tabs a{padding:9px 12px;font-size:12px}}
    </style>
</head>
<body>
    @include('frontend.partials.header')
    <main class="promo-page">
        <section class="promo-hero">
            <div class="promo-hero__content">
                <p class="promo-hero__eyebrow">{{ $menuBrand }}</p>
                <h1>{{ $isEnglish ? 'Promotions' : 'Khuyến mãi' }}</h1>
                <p class="promo-hero__sub">{{ $isEnglish ? 'A little something special for your next visit' : 'Ưu đãi đặc biệt dành cho trải nghiệm tiếp theo của bạn' }}</p>
            </div>
        </section>
        <div class="promo-wrap">
            <nav class="promo-tabs" aria-label="{{ $isEnglish ? 'Menu pages' : 'Các trang thực đơn' }}">
                <a href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'Our Menu' : 'Thực đơn' }}</a>
                <a href="{{ route('menu.must-try') }}">Must Try</a>
                <a href="{{ route('menu.combos') }}">Combo</a>
                <a href="{{ route('menu.promotions') }}" class="is-active">{{ $isEnglish ? 'Promotions' : 'Khuyến mãi' }}</a>
            </nav>
            <div class="promo-grid">
                @forelse ($promotions as $promotion)
                    @php
                        $promotionTitle = $isEnglish ? ($promotion->title_en ?: $promotion->title) : $promotion->title;
                        $promotionSummary = $isEnglish ? ($promotion->short_description_en ?: $promotion->short_description) : $promotion->short_description;
                        $promotionDescription = $isEnglish ? ($promotion->description_en ?: $promotion->description) : $promotion->description;
                    @endphp
                    <article class="promo-card">
                        @if ($promotion->menu_image_url)
                            <img class="promo-card__image" src="{{ $promotion->menu_image_url }}" alt="{{ $promotionTitle }}" loading="lazy">
                        @else
                            <div class="promo-card__image promo-card__fallback" role="img" aria-label="{{ $promotionTitle }}">{{ mb_substr($promotionTitle, 0, 1) }}</div>
                        @endif
                        <div class="promo-card__body">
                            <p class="promo-card__badge">{{ $isEnglish ? 'Special offer' : 'Ưu đãi đặc biệt' }}</p>
                            <h2 class="promo-card__title">{{ $promotionTitle }}</h2>
                            @if (filled($promotionSummary))<p class="promo-card__summary">{{ $promotionSummary }}</p>@endif
                            @if (filled($promotionDescription))<p class="promo-card__description">{{ $promotionDescription }}</p>@endif
                            @if ($promotion->start_date || $promotion->end_date)
                                <p class="promo-card__dates">{{ $isEnglish ? 'Valid' : 'Thời gian' }}: {{ $promotion->start_date?->format('d/m/Y') ?? '—' }} – {{ $promotion->end_date?->format('d/m/Y') ?? '—' }}</p>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="promo-empty">
                        <h2>{{ $isEnglish ? 'New promotions are coming soon' : 'Ưu đãi mới sẽ sớm được cập nhật' }}</h2>
                        <p>{{ $isEnglish ? 'Please check back soon and discover our menu in the meantime.' : 'Vui lòng quay lại sau. Trong lúc chờ, mời bạn khám phá thực đơn của chúng tôi.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        <section class="promo-cta">
            <p>{{ $isEnglish ? 'Make your next visit memorable' : 'Hẹn bạn trong trải nghiệm tiếp theo' }}</p>
            <a href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'EXPLORE OUR MENU' : 'KHÁM PHÁ THỰC ĐƠN' }}</a>
        </section>
    </main>
    @include('frontend.partials.footer')
</body>
</html>
