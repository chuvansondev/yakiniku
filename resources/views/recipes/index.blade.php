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
    <meta name="description" content="{{ $isEnglish ? 'Discover recipes and cooking inspiration from '.$menuBrand.'.' : 'Khám phá công thức và cảm hứng nấu ăn từ '.$menuBrand.'.' }}">
    <title>{{ $isEnglish ? 'Recipes' : 'Công thức' }} | {{ $menuBrand }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG_B.woff2') }}') format('woff2');font-weight:700;font-display:swap}
        @font-face{font-family:'Dense';src:url('{{ asset('fonts/Dense.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        .recipe-page{--recipe-red:#a42127;--recipe-ink:#332a26;--recipe-muted:#75645a;min-height:100vh;background:#fbf9f6;color:var(--recipe-ink);font-family:'UTM BryantLG',Arial,sans-serif}
        .recipe-page *{box-sizing:border-box}.recipe-hero{position:relative;display:grid;min-height:250px;place-items:center;overflow:hidden;background:linear-gradient(120deg,#241714,#493027 52%,#231a17);color:#fff;text-align:center}
        .recipe-hero::before{position:absolute;inset:0;opacity:.24;background:radial-gradient(ellipse at 50% 110%,#ba7b4a 0,transparent 55%),repeating-linear-gradient(135deg,transparent 0 8px,rgba(255,255,255,.035) 8px 9px);content:''}.recipe-hero::after{position:absolute;inset:18px;border:1px solid rgba(230,200,161,.3);content:''}
        .recipe-hero__content{position:relative;padding:34px 20px}.recipe-hero__eyebrow{margin:0 0 10px;color:#dfbd96;font-size:12px;font-weight:700;letter-spacing:.28em;text-transform:uppercase}
        .recipe-hero h1{margin:0;color:#fff;font-family:'UTM Pacific','Dense',serif;font-size:clamp(40px,6.5vw,66px);font-weight:400;letter-spacing:.035em;line-height:1.1}.recipe-hero__sub{max-width:560px;margin:15px auto 0;color:#e7d4c0;font-size:16px;line-height:1.55}
        .recipe-wrap{width:min(1160px,calc(100% - 48px));margin:auto;padding:48px 0 76px}.recipe-intro{max-width:700px;margin:0 auto 36px;color:var(--recipe-muted);font-size:17px;line-height:1.65;text-align:center}
        .recipe-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:28px}.recipe-card{overflow:hidden;border:1px solid #e8dfd7;background:#fff;box-shadow:0 10px 28px rgba(58,39,28,.055)}
        .recipe-card__image{display:block;width:100%;aspect-ratio:1.8;object-fit:cover;background:linear-gradient(135deg,#44342c,#a07a5f)}.recipe-card__fallback{display:grid;place-items:center;color:#ead8c4;font-family:Georgia,serif;font-size:60px}
        .recipe-card__body{padding:23px 25px 25px}.recipe-card__date{margin:0 0 9px;color:#9b8068;font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase}
        .recipe-card__title{margin:0;color:var(--recipe-red);font-family:'UTM Pacific','Dense',serif;font-size:26px;font-weight:400;line-height:1.25}.recipe-card__summary{margin:11px 0 0;color:var(--recipe-muted);font-size:16px;line-height:1.6}
        .recipe-card__details{margin-top:18px;border-top:1px solid #eee7e1}.recipe-card__details summary{padding:14px 0 0;color:var(--recipe-red);font-size:13px;font-weight:700;letter-spacing:.04em;cursor:pointer;list-style:none}.recipe-card__details summary::-webkit-details-marker{display:none}.recipe-card__details summary::after{float:right;content:'+';font-size:18px;line-height:14px}.recipe-card__details[open] summary::after{content:'−'}
        .recipe-card__content{padding-top:13px;color:#6f6259;font-size:15px;line-height:1.7;white-space:pre-line}
        .recipe-empty{grid-column:1/-1;padding:60px 24px;border:1px solid #e8dfd7;background:#fff;text-align:center}.recipe-empty h2{margin:0 0 9px;color:var(--recipe-red);font-family:'UTM Pacific','Dense',serif;font-size:29px;font-weight:400}.recipe-empty p{margin:0;color:var(--recipe-muted);font-size:16px}
        .recipe-cta{padding:47px 22px;background:#38271f;color:#fff;text-align:center}.recipe-cta p{margin:0 0 17px;color:#dfbd96;font-family:'UTM Pacific','Dense',serif;font-size:28px}.recipe-cta a{display:inline-flex;justify-content:center;padding:13px 22px;background:#f7f0e8;color:#6d3128;font-size:13px;font-weight:700;letter-spacing:.08em;text-decoration:none}
        @media(max-width:760px){.recipe-grid{grid-template-columns:1fr;gap:19px}.recipe-wrap{width:calc(100% - 34px);padding:34px 0 52px}.recipe-card__body{padding:20px}.recipe-hero{min-height:210px}}
    </style>
</head>
<body>
    @include('frontend.partials.header')
    <main class="recipe-page">
        <section class="recipe-hero">
            <div class="recipe-hero__content">
                <p class="recipe-hero__eyebrow">{{ $menuBrand }}</p>
                <h1>{{ $isEnglish ? 'Recipes' : 'Công thức' }}</h1>
                <p class="recipe-hero__sub">{{ $isEnglish ? 'Stories, techniques and inspiration from our kitchen' : 'Chia sẻ cảm hứng, bí quyết và câu chuyện từ căn bếp của chúng tôi' }}</p>
            </div>
        </section>
        <div class="recipe-wrap">
            <p class="recipe-intro">{{ $isEnglish ? 'Get to know the ingredients and craft behind the flavors you love.' : 'Khám phá nguyên liệu và bí quyết tạo nên những hương vị bạn yêu thích.' }}</p>
            <div class="recipe-grid">
                @forelse ($recipes as $recipe)
                    @php
                        $recipeTitle = $isEnglish ? ($recipe->title_en ?: $recipe->title) : $recipe->title;
                        $recipeSummary = $isEnglish ? ($recipe->short_description_en ?: $recipe->short_description) : $recipe->short_description;
                        $recipeContent = $isEnglish ? ($recipe->content_en ?: $recipe->content) : $recipe->content;
                    @endphp
                    <article class="recipe-card" id="recipe-{{ $recipe->slug }}">
                        @if ($recipe->menu_image_url)
                            <img class="recipe-card__image" src="{{ $recipe->menu_image_url }}" alt="{{ $recipeTitle }}" loading="lazy">
                        @else
                            <div class="recipe-card__image recipe-card__fallback" role="img" aria-label="{{ $recipeTitle }}">{{ mb_substr($recipeTitle, 0, 1) }}</div>
                        @endif
                        <div class="recipe-card__body">
                            @if ($recipe->published_at)<p class="recipe-card__date">{{ $recipe->published_at->format('d/m/Y') }}</p>@endif
                            <h2 class="recipe-card__title">{{ $recipeTitle }}</h2>
                            @if (filled($recipeSummary))<p class="recipe-card__summary">{{ $recipeSummary }}</p>@endif
                            @if (filled($recipeContent))
                                <details class="recipe-card__details">
                                    <summary>{{ $isEnglish ? 'Read the recipe' : 'Đọc công thức' }}</summary>
                                    <div class="recipe-card__content">{!! nl2br(e($recipeContent)) !!}</div>
                                </details>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="recipe-empty">
                        <h2>{{ $isEnglish ? 'Recipes are coming soon' : 'Công thức mới sẽ sớm được cập nhật' }}</h2>
                        <p>{{ $isEnglish ? 'Please check back soon for stories and inspiration from our kitchen.' : 'Vui lòng quay lại sau để đón đọc những chia sẻ từ căn bếp của chúng tôi.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        <section class="recipe-cta">
            <p>{{ $isEnglish ? 'Taste it at our table' : 'Thưởng thức hương vị tại nhà hàng' }}</p>
            <a href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'EXPLORE OUR MENU' : 'KHÁM PHÁ THỰC ĐƠN' }}</a>
        </section>
    </main>
    @include('frontend.partials.footer')
</body>
</html>
