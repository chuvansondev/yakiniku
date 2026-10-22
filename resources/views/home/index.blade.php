@php
    $isEnglish = app()->getLocale() === 'en';
    $homeBrand = setting('header_brand_name'.($isEnglish ? '_en' : '')) ?: config('app.name');
    $zaloUrl = setting('zalo_url');
    $facebookUrl = setting('facebook_url');
    $youtubeUrl = setting('youtube_url');
    $imageUrl = fn ($path) => filled($path)
        ? (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/']) ? $path : asset('storage/'.ltrim($path, '/')))
        : null;
@endphp
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    @include('frontend.partials.assets')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ setting('header_brand_name'.($isEnglish ? '_en' : '')) ?: config('app.name') }}</title>
    <link rel="preload" href="{{ asset('fonts/UTM%20BryantLG.woff2') }}" as="font" type="font/woff2" crossorigin fetchpriority="high">
    <link rel="preload" href="{{ asset('fonts/UTM%20BryantLG_B.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/UTM-Pacific-Standard.woff2') }}" as="font" type="font/woff2" crossorigin>
    <style>
        @font-face {
            font-family: 'UTM BryantLG';
            src: url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');
            font-style: normal;
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'UTM BryantLG';
            src: url('{{ asset('fonts/UTM%20BryantLG_B.woff2') }}') format('woff2');
            font-style: normal;
            font-weight: 700;
            font-display: swap;
        }
        @font-face {
            font-family: 'Dense';
            src: url('{{ asset('fonts/Dense-Thin.woff2') }}') format('woff2');
            font-style: normal;
            font-weight: 100;
            font-display: swap;
        }
        @font-face {
            font-family: 'Dense';
            src: url('{{ asset('fonts/Dense.woff2') }}') format('woff2');
            font-style: normal;
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'Dense';
            src: url('{{ asset('fonts/Dense-Bold.woff2') }}') format('woff2');
            font-style: normal;
            font-weight: 700;
            font-display: swap;
        }
        @font-face {
            font-family: 'UTM Pacific';
            src: url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');
            font-style: normal;
            font-weight: 400;
            font-display: swap;
        }
    </style>
    <style>
        html, body { width:100%; margin:0; }
        body { min-width:320px; }
        .home-page { --ink:#332a26; --muted:#75645a; --red:#a42127; --cream:#faf7f3; color:var(--ink); font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif; }
        .home-page * { box-sizing:border-box; }
        .home-page a { color:inherit; }
        .home-wrap { width:min(1180px,calc(100% - 40px)); margin-inline:auto; }
        .home-hero { position:relative; overflow:hidden; min-height:clamp(380px,52vw,660px); background:#261b17; color:#fff; }
        .home-hero__slides { position:relative; height:clamp(380px,52vw,660px); overflow:hidden; }
        .home-hero__slide { position:absolute; inset:0; display:flex; align-items:flex-end; padding:clamp(34px,8vw,100px); background:#30221d center/cover no-repeat; opacity:0; filter:blur(8px); transition:opacity .55s ease,filter .55s ease; pointer-events:none; }
        .home-hero__slide.is-active { opacity:1; filter:blur(0); pointer-events:auto; }
        .home-hero__slide::before { position:absolute; inset:0; background:linear-gradient(90deg,rgba(15,10,8,.62),rgba(15,10,8,.28) 58%,rgba(15,10,8,.04)); content:''; }
        .home-hero__content { position:absolute; top:50%; left:clamp(24px,8vw,100px); z-index:1; width:min(700px,calc(100% - 48px)); color:#fff; transform:translateY(-50%); }
        .home-hero h1.home-hero__brand { margin:0 0 14px; font-family:'UTM Pacific','Dense',sans-serif; font-size:clamp(38px,6vw,68px); font-weight:400; line-height:1.05; }
        .home-hero__description { max-width:640px; margin:0; font-family:'UTM BryantLG',Arial,sans-serif; font-size:clamp(16px,2vw,22px); font-weight:400; line-height:1.55; }
        .home-hero__actions { display:flex; flex-wrap:wrap; gap:10px; margin-top:25px; }
        .home-hero__book-row { margin-top:14px; }
        .home-hero__social { display:inline-flex; width:48px; height:48px; align-items:center; justify-content:center; padding:0; border:0; border-radius:50%; background:transparent; transition:transform .18s ease; }
        .home-hero__social img { display:block; width:100%; height:100%; object-fit:contain; }
        .home-hero__social--youtube img { transform:scale(1.4); }
        .home-hero__social:hover { transform:translateY(-2px); }
        .home-hero__book { display:inline-flex; min-width:150px; min-height:38px; align-items:center; justify-content:center; padding:7px 14px; border:0; background:#f5f1ed; color:#623e2a!important; font-family:'UTM BryantLG',Arial,sans-serif; font-size:13px; font-weight:700; letter-spacing:.035em; text-decoration:none; transition:background .18s ease,color .18s ease; }
        .home-hero__book:hover { background:#fff; }
        .home-hero__copy { position:relative; width:min(660px,100%); }
        .home-hero__controls { position:absolute; inset:50% 20px auto; z-index:2; display:flex; justify-content:space-between; transform:translateY(-50%); pointer-events:none; }
        .home-hero__arrow { display:grid; width:44px; height:44px; place-items:center; border:1px solid rgb(255 255 255 / .65); border-radius:50%; background:rgb(25 17 14 / .35); color:#fff; font-size:24px; cursor:pointer; pointer-events:auto; }
        .home-hero__arrow:hover { background:#fff; color:var(--ink); }
        .home-hero__dots { position:absolute; right:0; bottom:22px; left:0; z-index:2; display:flex; justify-content:center; gap:9px; }
        .home-hero__dot { display:block; width:12px; height:12px; flex:0 0 12px; cursor:pointer; }
        .home-hero__dot svg { display:block; width:12px; height:12px; }
        .home-hero__eyebrow,.home-kicker { margin:0 0 12px; color:#d8b68b; font-size:12px; font-weight:700; letter-spacing:.2em; text-transform:uppercase; }
        .home-hero h1 { margin:0; font-family:Georgia,'Times New Roman',serif; font-size:clamp(38px,7vw,76px); font-weight:500; line-height:1.04; }
        .home-hero__action,.home-button { display:inline-flex; align-items:center; justify-content:center; margin-top:24px; padding:13px 20px; border:1px solid currentColor; color:#fff; font-size:13px; font-weight:700; letter-spacing:.04em; text-decoration:none; transition:background .2s,color .2s; }
        .home-hero__action:hover { background:#fff; color:var(--ink); }
        .home-section { padding:clamp(58px,8vw,100px) 0; }
        .home-section--menu { position:relative; overflow:hidden; padding:34px 0 38px; background-color:#101112; background-image:repeating-linear-gradient(135deg,rgba(255,255,255,.018) 0,rgba(255,255,255,.018) 1px,transparent 1px,transparent 4px); color:#f5f1ed; }
        .home-section__head { display:flex; align-items:end; justify-content:space-between; gap:24px; margin-bottom:34px; }
        .home-section h2 { margin:0; font-family:Georgia,'Times New Roman',serif; font-size:clamp(32px,4vw,48px); font-weight:500; line-height:1.1; }
        .home-section__intro { max-width:480px; margin:12px 0 0; color:var(--muted); line-height:1.7; }
        .home-button { flex-shrink:0; margin:0; border-color:var(--red); color:var(--red); }
        .home-button:hover { background:var(--red); color:#fff; }
        .home-section--menu .home-wrap { width:min(1240px,calc(100% - 54px)); }
        .home-section--menu .home-section__head { position:relative; display:flex; align-items:center; justify-content:center; min-height:48px; margin-bottom:30px; text-align:center; }
        .home-section--menu .home-kicker,.home-section--menu .home-section__intro { display:none; }
        .home-section--menu h2,.home-section--restaurants .home-restaurant__title { color:#bd282d; font-family:'UTM Pacific','Dense',sans-serif; font-size:clamp(30px,4vw,42px); font-weight:400; letter-spacing:.035em; line-height:1.1; text-transform:none; }
        .home-section--menu .home-button { position:absolute; top:50%; right:0; display:inline-flex; margin:0; padding:10px 15px; border:0; background:#f5f1ed; color:#9c2529; font-family:'UTM BryantLG',Arial,sans-serif; font-size:13px; font-weight:700; letter-spacing:normal; transform:translateY(-50%); }
        .home-section--menu .home-button:hover { background:#fff; color:#9c2529; }
        .home-category-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); column-gap:22px; row-gap:24px; }
        .home-category { display:block; overflow:hidden; background:transparent; color:#ddd!important; text-align:center; text-decoration:none; }
        .home-category__image { display:block; width:100%; aspect-ratio:1.55; object-fit:cover; background:#292929; }
        .home-category__body { padding:13px 4px 0; }
        .home-category h3 { margin:0; font-family:'UTM Pacific','Dense',sans-serif; font-size:20px; font-weight:400; letter-spacing:.025em; text-transform:uppercase; }
        .home-restaurant h3 { margin:0; font-family:Georgia,'Times New Roman',serif; font-size:25px; font-weight:500; }
        .home-section--menu .home-category h3 { color:#ddd; }
        .home-category:hover h3 { color:#fff; }
        .home-category__desc { min-height:48px; margin:9px 0 16px; color:var(--muted); font-size:14px; line-height:1.6; }
        .home-dish-list { display:grid; gap:10px; margin:0; padding:0; list-style:none; }
        .home-dish { display:flex; justify-content:space-between; gap:12px; padding-top:10px; border-top:1px solid #eee8e3; font-size:14px; }
        .home-dish__price { flex-shrink:0; color:var(--red); font-weight:700; }
        .home-category__more { display:inline-block; margin-top:17px; color:var(--red)!important; font-size:13px; font-weight:700; text-decoration:none; }
        .home-menu-footer { display:none; }
        .home-section--restaurants { padding:42px 0; background:#623e2a; }
        .home-restaurant-grid { display:grid; grid-template-columns:1fr; gap:28px; }
        .home-restaurant { display:grid; grid-template-columns:minmax(0,1fr) minmax(320px,.9fr); align-items:center; gap:26px 44px; width:min(100%,1030px); margin-inline:auto; padding:30px clamp(24px,5vw,68px) 24px; background:transparent; color:#fff; }
        .home-restaurant__title { grid-column:1/-1; margin:0; text-align:center; }
        .home-restaurant__address { color:#fff; font-family:'UTM BryantLG',Arial,sans-serif; font-size:18px; font-weight:400; line-height:1.6; }
        .home-restaurant__address p { margin:0 0 12px; }
        .home-restaurant__map-column { display:grid; justify-items:end; gap:16px; }
        .home-restaurant__map { display:block; width:100%; aspect-ratio:1/1; border:0; background:#eee6df; }
        .home-restaurant__book { display:inline-flex; min-width:150px; min-height:38px; align-items:center; justify-content:center; padding:7px 14px; background:#f5f1ed; color:#623e2a!important; font-family:'UTM BryantLG',Arial,sans-serif; font-size:13px; font-weight:700; letter-spacing:.035em; text-decoration:none; }
        .home-restaurant__book:hover { background:#fff; }
        .home-empty { grid-column:1/-1; padding:30px; color:var(--muted); background:#fff; text-align:center; }
        @media(max-width:760px) { .home-category-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } .home-section__head { align-items:start; flex-direction:column; } .home-restaurant { grid-template-columns:1fr; gap:22px; } .home-restaurant__title { grid-column:auto; } .home-restaurant__map-column { justify-items:stretch; } .home-restaurant__book { justify-self:end; } }
        @media(max-width:520px) { .home-wrap { width:calc(100% - 28px); } .home-category-grid,.home-restaurant-grid { grid-template-columns:1fr; } .home-hero__slide { padding:30px 22px; } .home-restaurant { padding:24px 18px 20px; } .home-restaurant__map { aspect-ratio:4/3; } }
    </style>
</head>
<body>
    @include('frontend.partials.header')
    <main class="home-page">
        {{-- Section 1: banners --}}
        <section class="home-hero" aria-label="{{ $isEnglish ? 'Featured banners' : 'Banner nổi bật' }}">
            <div class="home-hero__slides" id="homeBannerSlides">
                @forelse ($banners as $banner)
                    @php
                        $bannerImage = $banner->homepage_image_url ?: $imageUrl($banner->image);
                    @endphp
                    <article class="home-hero__slide {{ $loop->first ? 'is-active' : '' }}" @if($bannerImage) @if($loop->first) style="background-image:url('{{ $bannerImage }}')" @else data-background-image="{{ $bannerImage }}" @endif @endif>
                    </article>
                @empty
                    <article class="home-hero__slide is-active">
                    </article>
                @endforelse
            </div>
            <div class="home-hero__content">
                <h1 class="home-hero__brand">{{ $homeBrand }}</h1>
                <p class="home-hero__description">{{ $isEnglish ? 'Enjoy the delicious taste of Japanese barbecue in every carefully selected slice of meat.' : 'Thưởng thức vị ngon nướng Nhật trong từng lát thịt tuyển chọn.' }}</p>
                <div class="home-hero__actions">
                    <a class="home-hero__social" href="{{ filled($zaloUrl) ? $zaloUrl : '#home-restaurants' }}" aria-label="ZaloQA" title="ZaloQA" @if(filled($zaloUrl)) target="_blank" rel="noopener noreferrer" @endif><img src="{{ asset('yakiniku/logo-zalo.png') }}" alt="" width="48" height="48"></a>
                    <a class="home-hero__social" href="{{ filled($facebookUrl) ? $facebookUrl : '#home-restaurants' }}" aria-label="Fanpage" title="Fanpage" @if(filled($facebookUrl)) target="_blank" rel="noopener noreferrer" @endif><img src="{{ asset('yakiniku/logo-facebook-blue.png') }}" alt="" width="48" height="48"></a>
                    <a class="home-hero__social home-hero__social--youtube" href="{{ filled($youtubeUrl) ? $youtubeUrl : '#home-restaurants' }}" aria-label="Youtube" title="Youtube" @if(filled($youtubeUrl)) target="_blank" rel="noopener noreferrer" @endif><img src="{{ asset('yakiniku/logo-youtube.png') }}" alt="" width="48" height="48"></a>
                </div>
                <div class="home-hero__book-row"><a class="home-hero__book" href="{{ url('/booking') }}">{{ $isEnglish ? 'BOOK NOW' : 'ĐẶT BÀN NGAY' }}</a></div>
            </div>
            @if ($banners->count() > 1)
                <div class="home-hero__controls" aria-label="{{ $isEnglish ? 'Banner controls' : 'Điều khiển banner' }}">
                    <button class="home-hero__arrow" type="button" data-banner-direction="previous" aria-label="{{ $isEnglish ? 'Previous banner' : 'Banner trước' }}">‹</button>
                    <button class="home-hero__arrow" type="button" data-banner-direction="next" aria-label="{{ $isEnglish ? 'Next banner' : 'Banner tiếp theo' }}">›</button>
                </div>
                <div class="home-hero__dots" aria-label="{{ $isEnglish ? 'Choose banner' : 'Chọn banner' }}">
                    @foreach ($banners as $banner)
                        <span class="home-hero__dot" data-banner-index="{{ $loop->index }}" role="button" tabindex="0" aria-label="{{ ($isEnglish ? 'Banner ' : 'Banner ') . ($loop->iteration) }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"><svg viewBox="0 0 12 12" aria-hidden="true"><circle cx="6" cy="6" r="4.5" stroke="#fff" stroke-width="1.5" fill="{{ $loop->first ? '#fff' : 'none' }}" style="stroke:#fff;fill:{{ $loop->first ? '#fff' : 'none' }}"></circle></svg></span>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Section 2: menu categories and featured dishes --}}
        <section class="home-section home-section--menu" id="home-menu">
            <div class="home-wrap">
                <div class="home-section__head">
                    <p class="home-kicker">{{ $isEnglish ? 'Made for sharing' : 'Cùng nhau thưởng thức' }}</p>
                    <h2>{{ $isEnglish ? 'MENU' : 'THỰC ĐƠN' }}</h2>
                    <p class="home-section__intro">{{ $isEnglish ? 'Find your next favorite across our menu collections.' : 'Tìm món yêu thích tiếp theo trong các danh mục thực đơn của chúng tôi.' }}</p>
                    <a class="home-button" href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'VIEW MENU' : 'XEM MENU' }}</a>
                </div>
                <div class="home-category-grid">
                    @forelse ($categories->take(6) as $category)
                        @php
                            $categoryName = $isEnglish ? ($category->name_en ?: $category->name) : $category->name;
                        @endphp
                        <a class="home-category" href="{{ route('menu.our-menu') }}">
                            @if ($categoryImage = ($category->homepage_image_url ?: $imageUrl($category->image)))
                                <img class="home-category__image" src="{{ $categoryImage }}" alt="{{ $categoryName }}" loading="lazy">
                            @else
                                <div class="home-category__image" role="img" aria-label="{{ $categoryName }}"></div>
                            @endif
                            <div class="home-category__body">
                                <h3>{{ $categoryName }}</h3>
                            </div>
                        </a>
                    @empty
                        <p class="home-empty">{{ $isEnglish ? 'Menu collections will appear here soon.' : 'Các danh mục thực đơn sẽ sớm được cập nhật.' }}</p>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Section 3: restaurant locations --}}
        <section class="home-section home-section--restaurants" id="home-restaurants">
            <div class="home-wrap">
                <div class="home-restaurant-grid">
                    @forelse ($restaurants as $restaurant)
                        @php
                            $restaurantName = $isEnglish ? ($restaurant->name_en ?: $restaurant->name) : $restaurant->name;
                            $restaurantAddress = $isEnglish ? ($restaurant->address_en ?: $restaurant->address) : $restaurant->address;
                            $mapQuery = filled($restaurant->latitude) && filled($restaurant->longitude)
                                ? $restaurant->latitude.','.$restaurant->longitude
                                : $restaurantAddress;
                            $mapEmbedUrl = filled($mapQuery)
                                ? 'https://www.google.com/maps?q='.urlencode($mapQuery).'&output=embed'
                                : null;
                        @endphp
                        <article class="home-restaurant">
                            <h2 class="home-restaurant__title">{{ $isEnglish ? 'Restaurant' : 'Nhà hàng' }}</h2>
                            <div class="home-restaurant__address">
                                @if (filled($restaurantAddress)) <p>{{ $restaurantAddress }}</p> @endif
                                @if (filled($restaurant->phone)) <p>{{ $isEnglish ? 'Phone' : 'Điện thoại' }}: {{ $restaurant->phone }}</p> @endif
                                @if (filled($restaurant->opening_time) || filled($restaurant->closing_time))
                                    <p>{{ $isEnglish ? 'Hours' : 'Giờ mở cửa' }}: {{ $restaurant->opening_time }} – {{ $restaurant->closing_time }}</p>
                                @endif
                            </div>
                            <div class="home-restaurant__map-column">
                                @if ($mapEmbedUrl)
                                    <iframe class="home-restaurant__map" src="{{ $mapEmbedUrl }}" title="{{ $isEnglish ? 'Map for '.$restaurantName : 'Bản đồ '.$restaurantName }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                                @elseif ($restaurantImage = $imageUrl($restaurant->image))
                                    <img class="home-restaurant__map" src="{{ $restaurantImage }}" alt="{{ $restaurantName }}" loading="lazy">
                                @endif
                                <a class="home-restaurant__book" href="{{ url('/booking') }}">{{ $isEnglish ? 'BOOK NOW' : 'ĐẶT BÀN NGAY' }}</a>
                            </div>
                        </article>
                    @empty
                        <p class="home-empty">{{ $isEnglish ? 'Restaurant locations will appear here soon.' : 'Thông tin các cơ sở sẽ sớm được cập nhật.' }}</p>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
    @include('frontend.partials.footer')
    @if ($banners->count() > 1)
        <script>
            (() => {
                const track = document.getElementById('homeBannerSlides');
                if (!track) return;
                const slides = [...track.querySelectorAll('.home-hero__slide')];
                const dots = [...document.querySelectorAll('[data-banner-index]')];
                let active = 0;
                let timer;
                const show = (index) => {
                    active = (index + slides.length) % slides.length;
                    [active, (active + 1) % slides.length].forEach((slideIndex) => {
                        const slide = slides[slideIndex];
                        const image = slide.dataset.backgroundImage;
                        if (image) {
                            slide.style.backgroundImage = `url("${image}")`;
                            delete slide.dataset.backgroundImage;
                        }
                    });
                    slides.forEach((slide, i) => slide.classList.toggle('is-active', i === active));
                    dots.forEach((dot, i) => {
                        dot.setAttribute('aria-current', i === active ? 'true' : 'false');
                        dot.querySelector('circle').style.fill = i === active ? '#fff' : 'none';
                    });
                };
                const start = () => { clearInterval(timer); timer = setInterval(() => show(active + 1), 6000); };
                document.querySelectorAll('[data-banner-direction]').forEach((button) => button.addEventListener('click', () => {
                    show(active + (button.dataset.bannerDirection === 'next' ? 1 : -1)); start();
                }));
                dots.forEach((dot) => {
                    const select = () => { show(Number(dot.dataset.bannerIndex)); start(); };
                    dot.addEventListener('click', select);
                    dot.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); select(); }
                    });
                });
                track.closest('.home-hero').addEventListener('mouseenter', () => clearInterval(timer));
                track.closest('.home-hero').addEventListener('mouseleave', start);
                track.closest('.home-hero').addEventListener('focusin', () => clearInterval(timer));
                track.closest('.home-hero').addEventListener('focusout', start);
                start();
            })();
        </script>
    @endif
</body>
</html>
