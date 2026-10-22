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
    <meta name="description" content="{{ $isEnglish ? 'Explore tips, guides and inspiration from '.$menuBrand.'.' : 'Khám phá những bí kíp, hướng dẫn và cảm hứng từ '.$menuBrand.'.' }}">
    <title>{{ $isEnglish ? 'Tips & Guides' : 'Bí kíp' }} | {{ $menuBrand }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG_B.woff2') }}') format('woff2');font-weight:700;font-display:swap}
        @font-face{font-family:'Dense';src:url('{{ asset('fonts/Dense.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        .tips-page{--tips-red:#a42127;--tips-ink:#332a26;--tips-muted:#75645a;min-height:100vh;background:#fbf9f6;color:var(--tips-ink);font-family:'UTM BryantLG',Arial,sans-serif}
        .tips-page *{box-sizing:border-box}.tips-hero{position:relative;display:grid;min-height:250px;place-items:center;overflow:hidden;background:linear-gradient(120deg,#241714,#493027 52%,#231a17);color:#fff;text-align:center}
        .tips-hero::before{position:absolute;inset:0;opacity:.24;background:radial-gradient(ellipse at 50% 110%,#ba7b4a 0,transparent 55%),repeating-linear-gradient(135deg,transparent 0 8px,rgba(255,255,255,.035) 8px 9px);content:''}.tips-hero::after{position:absolute;inset:18px;border:1px solid rgba(230,200,161,.3);content:''}
        .tips-hero__content{position:relative;padding:34px 20px}.tips-hero__eyebrow{margin:0 0 10px;color:#dfbd96;font-size:12px;font-weight:700;letter-spacing:.28em;text-transform:uppercase}
        .tips-hero h1{margin:0;color:#fff;font-family:'UTM Pacific','Dense',serif;font-size:clamp(40px,6.5vw,66px);font-weight:400;letter-spacing:.035em;line-height:1.1}.tips-hero__sub{max-width:560px;margin:15px auto 0;color:#e7d4c0;font-size:16px;line-height:1.55}
        .tips-wrap{width:min(1160px,calc(100% - 48px));margin:auto;padding:48px 0 76px}.tips-intro{max-width:700px;margin:0 auto 36px;color:var(--tips-muted);font-size:17px;line-height:1.65;text-align:center}
        .tips-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:28px}.tips-card{overflow:hidden;border:1px solid #e8dfd7;background:#fff;box-shadow:0 10px 28px rgba(58,39,28,.055)}
        .tips-card__image{display:block;width:100%;aspect-ratio:1.8;object-fit:cover;background:linear-gradient(135deg,#44342c,#a07a5f)}.tips-card__fallback{display:grid;place-items:center;color:#ead8c4;font-family:Georgia,serif;font-size:60px}
        .tips-card__body{padding:23px 25px 25px}.tips-card__date{margin:0 0 9px;color:#9b8068;font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase}
        .tips-card__title{margin:0;color:var(--tips-red);font-family:'UTM Pacific','Dense',serif;font-size:26px;font-weight:400;line-height:1.25}.tips-card__summary{margin:11px 0 0;color:var(--tips-muted);font-size:16px;line-height:1.6}
        .tips-card__details{margin-top:18px;border-top:1px solid #eee7e1}.tips-card__details summary{padding:14px 0 0;color:var(--tips-red);font-size:13px;font-weight:700;letter-spacing:.04em;cursor:pointer;list-style:none}.tips-card__details summary::-webkit-details-marker{display:none}.tips-card__details summary::after{float:right;content:'+';font-size:18px;line-height:14px}.tips-card__details[open] summary::after{content:'−'}
        .tips-card__content{padding-top:13px;color:#6f6259;font-size:15px;line-height:1.7;white-space:pre-line}
        .tips-empty{grid-column:1/-1;padding:60px 24px;border:1px solid #e8dfd7;background:#fff;text-align:center}.tips-empty h2{margin:0 0 9px;color:var(--tips-red);font-family:'UTM Pacific','Dense',serif;font-size:29px;font-weight:400}.tips-empty p{margin:0;color:var(--tips-muted);font-size:16px}
        .tips-cta{padding:47px 22px;background:#38271f;color:#fff;text-align:center}.tips-cta p{margin:0 0 17px;color:#dfbd96;font-family:'UTM Pacific','Dense',serif;font-size:28px}.tips-cta a{display:inline-flex;justify-content:center;padding:13px 22px;background:#f7f0e8;color:#6d3128;font-size:13px;font-weight:700;letter-spacing:.08em;text-decoration:none}
        @media(max-width:760px){.tips-grid{grid-template-columns:1fr;gap:19px}.tips-wrap{width:calc(100% - 34px);padding:34px 0 52px}.tips-card__body{padding:20px}.tips-hero{min-height:210px}}
    </style>
</head>
<body>
    @include('frontend.partials.header')
    <main class="tips-page">
        <section class="tips-hero">
            <div class="tips-hero__content">
                <p class="tips-hero__eyebrow">{{ $menuBrand }}</p>
                <h1>{{ $isEnglish ? 'Tips & Guides' : 'Bí kíp' }}</h1>
                <p class="tips-hero__sub">{{ $isEnglish ? 'Helpful ideas and little secrets for a richer dining experience' : 'Những chia sẻ hữu ích giúp trải nghiệm ẩm thực thêm trọn vẹn' }}</p>
            </div>
        </section>
        <div class="tips-wrap">
            <p class="tips-intro">{{ $isEnglish ? 'From choosing the right cut to savoring every bite, explore the details that make a meal memorable.' : 'Từ cách chọn phần thịt đến bí quyết thưởng thức, cùng khám phá những điều làm nên một bữa ăn đáng nhớ.' }}</p>
            <div class="tips-grid">
                @forelse ($tips as $tip)
                    @php
                        $tipTitle = $isEnglish ? ($tip->title_en ?: $tip->title) : $tip->title;
                        $tipSummary = $isEnglish ? ($tip->short_description_en ?: $tip->short_description) : $tip->short_description;
                        $tipContent = $isEnglish ? ($tip->content_en ?: $tip->content) : $tip->content;
                    @endphp
                    <article class="tips-card" id="tip-{{ $tip->slug }}">
                        @if ($tip->page_image_url)
                            <img class="tips-card__image" src="{{ $tip->page_image_url }}" alt="{{ $tipTitle }}" loading="lazy">
                        @else
                            <div class="tips-card__image tips-card__fallback" role="img" aria-label="{{ $tipTitle }}">{{ mb_substr($tipTitle, 0, 1) }}</div>
                        @endif
                        <div class="tips-card__body">
                            @if ($tip->published_at)<p class="tips-card__date">{{ $tip->published_at->format('d/m/Y') }}</p>@endif
                            <h2 class="tips-card__title">{{ $tipTitle }}</h2>
                            @if (filled($tipSummary))<p class="tips-card__summary">{{ $tipSummary }}</p>@endif
                            @if (filled($tipContent))
                                <details class="tips-card__details">
                                    <summary>{{ $isEnglish ? 'Read more' : 'Đọc thêm' }}</summary>
                                    <div class="tips-card__content">{!! nl2br(e($tipContent)) !!}</div>
                                </details>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="tips-empty">
                        <h2>{{ $isEnglish ? 'New tips are coming soon' : 'Bí kíp mới sẽ sớm được cập nhật' }}</h2>
                        <p>{{ $isEnglish ? 'Please check back soon for fresh ideas and inspiration.' : 'Vui lòng quay lại sau để đón đọc những chia sẻ và cảm hứng mới.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        <section class="tips-cta">
            <p>{{ $isEnglish ? 'Put your new knowledge to the test' : 'Thử áp dụng bí kíp trong bữa ăn tiếp theo' }}</p>
            <a href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'EXPLORE OUR MENU' : 'KHÁM PHÁ THỰC ĐƠN' }}</a>
        </section>
    </main>
    @include('frontend.partials.footer')
</body>
</html>
