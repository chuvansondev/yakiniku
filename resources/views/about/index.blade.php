@php
    $isEnglish = app()->getLocale() === 'en';
    $aboutBrand = setting('header_brand_name'.($isEnglish ? '_en' : '')) ?: config('app.name');
    $aboutDescription = localized_setting('footer_description');
@endphp
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    @include('frontend.partials.assets')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $isEnglish ? 'Learn more about '.$aboutBrand.', our approach to Japanese yakiniku and our restaurant.' : 'Tìm hiểu về '.$aboutBrand.', phong cách yakiniku Nhật Bản và nhà hàng của chúng tôi.' }}">
    <title>{{ $isEnglish ? 'About Us' : 'Về chúng tôi' }} | {{ $aboutBrand }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG_B.woff2') }}') format('woff2');font-weight:700;font-display:swap}
        @font-face{font-family:'Dense';src:url('{{ asset('fonts/Dense.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        .about-page{--about-red:#a42127;--about-ink:#332a26;--about-muted:#75645a;min-height:100vh;background:#fbf9f6;color:var(--about-ink);font-family:'UTM BryantLG',Arial,sans-serif}
        .about-page *{box-sizing:border-box}.about-hero{position:relative;display:grid;min-height:360px;place-items:center;overflow:hidden;background:linear-gradient(120deg,rgba(25,17,14,.88),rgba(61,42,32,.76)),radial-gradient(ellipse at 50% 110%,#ba7b4a 0,transparent 58%),#30221d;color:#fff;text-align:center}
        .about-hero::before{position:absolute;inset:0;opacity:.2;background:repeating-linear-gradient(135deg,transparent 0 8px,rgba(255,255,255,.04) 8px 9px);content:''}.about-hero::after{position:absolute;inset:18px;border:1px solid rgba(230,200,161,.3);content:''}
        .about-hero__content{position:relative;max-width:780px;padding:45px 25px}.about-hero__eyebrow{margin:0 0 12px;color:#dfbd96;font-size:12px;font-weight:700;letter-spacing:.3em;text-transform:uppercase}
        .about-hero h1{margin:0;color:#fff;font-family:'UTM Pacific','Dense',serif;font-size:clamp(44px,7vw,76px);font-weight:400;letter-spacing:.035em;line-height:1.05}.about-hero__copy{max-width:610px;margin:20px auto 0;color:#f0e5da;font-size:18px;line-height:1.65}
        .about-wrap{width:min(1160px,calc(100% - 48px));margin:auto}.about-story{display:grid;grid-template-columns:.9fr 1.1fr;align-items:center;gap:64px;padding:82px 0}
        .about-story__mark{position:relative;display:grid;min-height:360px;place-items:center;overflow:hidden;background:linear-gradient(145deg,#35251e,#875f45);color:#ead4bf;text-align:center}
        .about-story__mark::before{position:absolute;inset:15px;border:1px solid rgba(230,200,161,.35);content:''}.about-story__mark span{position:relative;font-family:'UTM Pacific','Dense',serif;font-size:clamp(38px,6vw,72px);letter-spacing:.07em}
        .about-kicker{margin:0 0 12px;color:#a17a59;font-size:11px;font-weight:700;letter-spacing:.2em;text-transform:uppercase}.about-heading{margin:0;color:var(--about-red);font-family:'UTM Pacific','Dense',serif;font-size:clamp(31px,4vw,45px);font-weight:400;line-height:1.15}
        .about-copy{margin:18px 0 0;color:var(--about-muted);font-size:17px;line-height:1.8}.about-story__actions{display:flex;flex-wrap:wrap;gap:11px;margin-top:25px}.about-button{display:inline-flex;align-items:center;justify-content:center;min-height:45px;padding:11px 18px;border:1px solid var(--about-red);background:var(--about-red);color:#fff!important;font-size:13px;font-weight:700;letter-spacing:.06em;text-decoration:none}.about-button--light{background:transparent;color:var(--about-red)!important}
        .about-values{padding:69px 0 78px;background:#f1ebe5}.about-values__head{max-width:700px;margin:0 auto 35px;text-align:center}.about-values__grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:19px}.about-value{padding:28px 25px;border:1px solid #e3d8cd;background:#fbf9f6;text-align:center}.about-value__number{display:grid;width:44px;height:44px;place-items:center;margin:0 auto 17px;border:1px solid #b99675;border-radius:50%;color:var(--about-red);font-family:Georgia,serif;font-size:18px}.about-value h3{margin:0;color:#60483a;font-family:'UTM Pacific','Dense',serif;font-size:22px;font-weight:400}.about-value p{margin:11px 0 0;color:var(--about-muted);font-size:15px;line-height:1.65}
        .about-locations{padding:78px 0}.about-locations__head{margin-bottom:30px;text-align:center}.about-location-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:22px}.about-location{overflow:hidden;border:1px solid #e8dfd7;background:#fff}.about-location__image{display:block;width:100%;aspect-ratio:1.9;object-fit:cover;background:#e9e0d8}.about-location__body{padding:22px 23px}.about-location h3{margin:0;color:var(--about-red);font-family:'UTM Pacific','Dense',serif;font-size:23px;font-weight:400}.about-location p{margin:10px 0 0;color:var(--about-muted);font-size:15px;line-height:1.6}.about-location__map{display:block;width:100%;height:190px;margin-top:18px;border:0;background:#eee6df}.about-location__link{display:inline-block;margin-top:13px;color:var(--about-red);font-size:13px;font-weight:700;text-decoration:none}
        .about-no-locations{padding:30px;background:#f1ebe5;color:var(--about-muted);text-align:center}.about-cta{padding:49px 22px;background:#38271f;color:#fff;text-align:center}.about-cta p{margin:0 0 17px;color:#dfbd96;font-family:'UTM Pacific','Dense',serif;font-size:28px}.about-cta .about-button{border-color:#f7f0e8;background:#f7f0e8;color:#6d3128!important}
        @media(max-width:760px){.about-hero{min-height:290px}.about-wrap{width:calc(100% - 34px)}.about-story{grid-template-columns:1fr;gap:30px;padding:48px 0}.about-story__mark{min-height:220px;order:2}.about-values{padding:48px 0}.about-values__grid,.about-location-grid{grid-template-columns:1fr}.about-locations{padding:52px 0}.about-copy{font-size:16px}.about-location__map{height:220px}}
    </style>
</head>
<body>
    @include('frontend.partials.header')
    <main class="about-page">
        <section class="about-hero">
            <div class="about-hero__content">
                <p class="about-hero__eyebrow">{{ $aboutBrand }}</p>
                <h1>{{ $isEnglish ? 'About Us' : 'Về chúng tôi' }}</h1>
                <p class="about-hero__copy">{{ $isEnglish ? 'A place to gather around the warmth of Japanese yakiniku and enjoy good food together.' : 'Nơi mọi người quây quần bên bếp nướng Nhật Bản và cùng thưởng thức những món ngon.' }}</p>
            </div>
        </section>

        <section class="about-wrap about-story">
            <div class="about-story__mark" aria-hidden="true"><span>{{ $aboutBrand }}</span></div>
            <div>
                <p class="about-kicker">{{ $isEnglish ? 'Our story' : 'Câu chuyện của chúng tôi' }}</p>
                <h2 class="about-heading">{{ $isEnglish ? 'Good food brings us closer' : 'Món ngon gắn kết mọi người' }}</h2>
                <p class="about-copy">{{ filled($aboutDescription) ? $aboutDescription : ($isEnglish ? 'We bring the Japanese yakiniku experience to the table with carefully prepared ingredients, the pleasure of grilling together, and warm hospitality. Each visit is an invitation to slow down, share a meal, and make time for one another.' : 'Chúng tôi mang trải nghiệm yakiniku Nhật Bản đến bàn ăn qua những nguyên liệu được chuẩn bị chỉn chu, niềm vui cùng nhau nướng thịt và sự tiếp đón thân tình. Mỗi lần ghé thăm là dịp để chậm lại, sẻ chia bữa ăn và dành thời gian cho nhau.') }}</p>
                <div class="about-story__actions">
                    <a class="about-button" href="{{ route('menu.our-menu') }}">{{ $isEnglish ? 'EXPLORE OUR MENU' : 'KHÁM PHÁ THỰC ĐƠN' }}</a>
                    <a class="about-button about-button--light" href="{{ url('/booking') }}">{{ $isEnglish ? 'BOOK A TABLE' : 'ĐẶT BÀN' }}</a>
                </div>
            </div>
        </section>

        <section class="about-values">
            <div class="about-wrap">
                <div class="about-values__head">
                    <p class="about-kicker">{{ $isEnglish ? 'What matters to us' : 'Điều chúng tôi trân trọng' }}</p>
                    <h2 class="about-heading">{{ $isEnglish ? 'The experience, in every detail' : 'Trọn vẹn trong từng trải nghiệm' }}</h2>
                </div>
                <div class="about-values__grid">
                    <article class="about-value"><span class="about-value__number">01</span><h3>{{ $isEnglish ? 'Japanese yakiniku' : 'Phong vị yakiniku Nhật' }}</h3><p>{{ $isEnglish ? 'A meal shaped by the pleasure of grilling and sharing at the table.' : 'Bữa ăn trọn vị với niềm vui tự tay nướng và sẻ chia bên bàn.' }}</p></article>
                    <article class="about-value"><span class="about-value__number">02</span><h3>{{ $isEnglish ? 'Thoughtful selection' : 'Tuyển chọn chỉn chu' }}</h3><p>{{ $isEnglish ? 'Ingredients and dishes are prepared with care for a balanced meal.' : 'Nguyên liệu và món ăn được chuẩn bị cẩn thận để tạo nên bữa ăn hài hòa.' }}</p></article>
                    <article class="about-value"><span class="about-value__number">03</span><h3>{{ $isEnglish ? 'Time together' : 'Khoảnh khắc bên nhau' }}</h3><p>{{ $isEnglish ? 'A welcoming setting for friends, family and occasions worth celebrating.' : 'Không gian thân tình dành cho bạn bè, gia đình và những dịp đáng nhớ.' }}</p></article>
                </div>
            </div>
        </section>

        <section class="about-wrap about-locations">
            <div class="about-locations__head">
                <p class="about-kicker">{{ $isEnglish ? 'Come visit' : 'Ghé thăm chúng tôi' }}</p>
                <h2 class="about-heading">{{ $isEnglish ? 'Our restaurants' : 'Nhà hàng của chúng tôi' }}</h2>
            </div>
            <div class="about-location-grid">
                @forelse ($restaurants as $restaurant)
                    @php
                        $restaurantName = $isEnglish ? ($restaurant->name_en ?: $restaurant->name) : $restaurant->name;
                        $restaurantAddress = $isEnglish ? ($restaurant->address_en ?: $restaurant->address) : $restaurant->address;
                    @endphp
                    <article class="about-location">
                        @if ($restaurant->page_image_url)<img class="about-location__image" src="{{ $restaurant->page_image_url }}" alt="{{ $restaurantName }}" loading="lazy">@endif
                        <div class="about-location__body">
                            <h3>{{ $restaurantName }}</h3>
                            @if (filled($restaurantAddress))<p>{{ $restaurantAddress }}</p>@endif
                            @if (filled($restaurant->phone))<p>{{ $isEnglish ? 'Phone' : 'Điện thoại' }}: {{ $restaurant->phone }}</p>@endif
                            @if (filled($restaurant->opening_time) || filled($restaurant->closing_time))<p>{{ $isEnglish ? 'Hours' : 'Giờ mở cửa' }}: {{ $restaurant->opening_time }} – {{ $restaurant->closing_time }}</p>@endif
                            @if ($restaurant->page_map_embed_url)<iframe class="about-location__map" src="{{ $restaurant->page_map_embed_url }}" title="{{ $isEnglish ? 'Map for '.$restaurantName : 'Bản đồ '.$restaurantName }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>@endif
                            <a class="about-location__link" href="{{ $restaurant->page_map_url }}" target="_blank" rel="noopener noreferrer">{{ $isEnglish ? 'GET DIRECTIONS' : 'XEM ĐƯỜNG ĐI' }} →</a>
                        </div>
                    </article>
                @empty
                    <p class="about-no-locations">{{ $isEnglish ? 'Restaurant information will be added soon.' : 'Thông tin nhà hàng sẽ sớm được cập nhật.' }}</p>
                @endforelse
            </div>
        </section>
        <section class="about-cta"><p>{{ $isEnglish ? 'We look forward to welcoming you' : 'Hẹn gặp bạn tại nhà hàng' }}</p><a class="about-button" href="{{ url('/booking') }}">{{ $isEnglish ? 'BOOK NOW' : 'ĐẶT BÀN NGAY' }}</a></section>
    </main>
    @include('frontend.partials.footer')
</body>
</html>
