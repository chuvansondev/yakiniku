<style>
    html, body { width: 100%; margin: 0; }
    .site-header {
        --header-ink: #493b35;
        font-family: 'UTM BryantLG', Arial, sans-serif;
        position: relative;
        z-index: 20;
        width: 100%;
        color: var(--header-ink);
        background: rgba(255, 255, 255, .94);
        border-bottom: 1px solid rgba(73, 59, 53, .12);
        box-shadow: 0 5px 22px rgba(45, 31, 24, .07);
        backdrop-filter: blur(12px);
    }
    .site-header *, .site-header *::before, .site-header *::after { box-sizing: border-box; }
    .site-header__inner {
        width: min(100% - 48px, 1440px);
        min-height: 88px;
        margin-inline: auto;
        display: grid;
        grid-template-columns: minmax(190px, 1fr) auto minmax(190px, 1fr);
        align-items: center;
        gap: 24px;
    }
    .site-header__brand {
        display: inline-flex;
        align-items: center;
        gap: 11px;
        justify-self: start;
        color: #332a26;
        text-decoration: none;
        white-space: nowrap;
        grid-column: 1;
        grid-row: 1;
    }
    .site-header__brand-mark {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border: 1px solid #a42127;
        border-radius: 50%;
        color: #a42127;
        font-family: 'UTM BryantLG', Arial, sans-serif;
        font-size: 22px;
        font-weight: 700;
    }
    .site-header__brand-name { font-family: 'UTM BryantLG', Arial, sans-serif; font-size: 21px; font-weight: 700; letter-spacing: -.035em; }
    .site-header__nav { display: flex; grid-column: 2; grid-row: 1; align-items: center; justify-content: center; gap: clamp(18px, 2.6vw, 42px); }
    .site-header__item { position: relative; }
    .site-header__submenu { position: absolute; z-index: 30; top: 100%; left: 50%; display: none; min-width: 210px; padding: 8px; border: 1px solid rgba(73, 59, 53, .12); border-radius: 4px; background: #fff; box-shadow: 0 10px 28px rgba(45, 31, 24, .14); transform: translateX(-50%); }
    .site-header__item:hover .site-header__submenu, .site-header__item:focus-within .site-header__submenu { display: block; }
    .site-header__submenu-link { display: block; padding: 10px 12px; border-radius: 3px; color: #65534a; font-size: 14px; text-decoration: none; white-space: nowrap; }
    .site-header__submenu-link:hover, .site-header__submenu-link:focus { background: #f8f3f0; color: #a42127; }
    .site-header__link {
        position: relative;
        padding-block: 8px;
        color: #65534a;
        font-size: 16px;
        font-weight: 500;
        text-decoration: none;
        white-space: nowrap;
        transition: color .18s ease;
    }
    .site-header__link::after { position: absolute; right: 0; bottom: 1px; left: 0; height: 2px; background: #a42127; content: ''; transform: scaleX(0); transform-origin: left; transition: transform .18s ease; }
    .site-header__link:hover, .site-header__link.is-active { color: #a42127; }
    .site-header__link:hover::after, .site-header__link.is-active::after { transform: scaleX(1); }
    .site-header__controls { display: flex; grid-column: 3; grid-row: 1; justify-self: end; align-items: center; gap: 12px; }
    .site-header__account { display:flex; align-items:center; gap:10px; color:#65534a; font-size:14px; white-space:nowrap; }
    .site-header__account a { color:#8f2527; text-decoration:none; font-weight:700; }
    .site-header__account button { padding:0; border:0; background:none; color:#8f2527; font:inherit; cursor:pointer; }
    .site-account { position:relative; }
    .site-header__account .site-account__trigger { display:flex; align-items:center; gap:8px; min-height:44px; margin:0; padding:4px 8px 4px 4px; border:1px solid #eadfd5; border-radius:24px; background:#fff; }
    .site-account__avatar { display:grid; place-items:center; flex-shrink:0; width:34px; height:34px; border-radius:50%; background:#a42127; color:#fff; font-weight:700; }
    .site-account__name { max-width:110px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .site-account__chevron { transition:transform .15s ease; }
    .site-account__trigger[aria-expanded="true"] .site-account__chevron { transform:rotate(180deg); }
    .site-account__panel { position:absolute; top:calc(100% + 10px); right:0; z-index:40; width:min(250px,calc(100vw - 24px)); padding:8px; border:1px solid #eadfd5; border-radius:10px; background:#fff; box-shadow:0 12px 32px #38271c24; white-space:normal; }
    .site-account__panel[hidden] { display:none; }
    .site-account__identity { padding:8px 12px 12px; border-bottom:1px solid #eadfd5; margin-bottom:4px; overflow-wrap:anywhere; }
    .site-account__identity strong,.site-account__identity small { display:block; }
    .site-account__identity small { margin-top:3px; color:#75645a; }
    .site-header__account .site-account__option { display:block; width:100%; min-height:44px; margin:0; padding:11px 12px; border:0; border-radius:6px; background:none; color:#493b35; text-align:left; font:inherit; text-decoration:none; }
    .site-account__panel form { margin:0; }
    .site-header__account .site-account__option:hover,.site-header__account .site-account__option:focus-visible { background:#f8f3f0; color:#a42127; }
    .site-account__trigger:focus-visible { outline:2px solid #a42127; outline-offset:3px; }
    @media(max-width:1200px) { .site-account__name { display:none; } }
    @media(max-width:480px) { .site-header__brand { min-width:0; } .site-header__brand img { max-width:100%; } .site-header__inner { grid-template-columns:minmax(0,1fr) auto; } .site-header__controls { gap:6px; } .site-header__account .site-account__trigger { padding:3px; } .site-account__chevron { display:none; } }
    .site-header__locale { display: inline-flex; align-items: center; gap: 4px; padding: 5px; border: 1px solid rgba(73, 59, 53, .22); border-radius: 3px; }
    .site-header__locale button { min-width: 48px; padding: 8px 10px; border: 0; border-radius: 2px; background: transparent; color: #594c45; font: inherit; font-size: 14px; font-weight: 600; cursor: pointer; transition: background .18s ease, color .18s ease; }
    .site-header__locale button:hover { background: #f4eeea; }
    .site-header__locale button.is-active { background: #a42127; color: #fff; }
    .site-header__toggle { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; clip-path: inset(50%); }
    .site-header__toggle-label { display: none; width: 42px; height: 42px; align-items: center; justify-content: center; border: 1px solid rgba(73, 59, 53, .24); border-radius: 3px; cursor: pointer; }
    .site-header__toggle-label span, .site-header__toggle-label span::before, .site-header__toggle-label span::after { position: relative; display: block; width: 19px; height: 2px; background: #493b35; content: ''; }
    .site-header__toggle-label span::before { position: absolute; top: -6px; }
    .site-header__toggle-label span::after { position: absolute; top: 6px; }
    .welcome-page { display: block !important; min-height: 100vh; padding: 0 !important; }
    .welcome-page > header.w-full { display: none; }
    @media (max-width: 980px) {
        .site-header__inner { width: min(100% - 32px, 760px); min-height: 76px; grid-template-columns: 1fr auto; gap: 10px 16px; }
        .site-header__controls { grid-column: 2; grid-row: 1; gap: 10px; }
        .site-header__toggle-label { display: inline-flex; }
        .site-header__nav { display: none; grid-column: 1 / -1; grid-row: 2; align-items: stretch; padding: 0 0 14px; }
        .site-header__toggle:checked ~ .site-header__nav { display: flex; flex-direction: column; gap: 2px; }
        .site-header__link { padding: 11px 8px; }
        .site-header__link::after { display: none; }
        .site-header__item { width: 100%; }
        .site-header__submenu { position: static; display: block; min-width: 0; padding: 0 0 4px 14px; border: 0; border-radius: 0; box-shadow: none; transform: none; }
        .site-header__submenu-link { padding: 8px; white-space: normal; }
    }
    @media (max-width: 480px) {
        .site-header__inner { width: calc(100% - 24px); min-height: 68px; }
        .site-header__brand-mark { width: 36px; height: 36px; font-size: 19px; }
        .site-header__brand-name { font-size: 18px; }
        .site-header__locale { gap: 0; padding: 3px; }
        .site-header__locale button { min-width: 39px; padding: 7px 6px; font-size: 12px; }
        .site-header__toggle-label { width: 38px; height: 38px; }
    }
    @media (max-width: 980px) { .site-header__account { white-space:normal; } }
</style>

@php
    $languageSuffix = app()->getLocale() === 'en' ? '_en' : '';
    $headerBrand = setting('header_brand_name'.$languageSuffix);
    $headerLogo = setting('header_logo_url');
    $headerLogoUrl = filled($headerLogo)
        ? (\Illuminate\Support\Str::startsWith($headerLogo, ['http://', 'https://', '/'])
            ? $headerLogo
            : asset('storage/'.ltrim($headerLogo, '/')))
        : null;
    $headerHomeLabel = setting('header_home_label'.$languageSuffix);
    $headerMenuLabel = setting('header_menu_label'.$languageSuffix);
    $headerPromotionsLabel = setting('header_promotions_label'.$languageSuffix);
    $headerSecretLabel = setting('header_secret_label'.$languageSuffix);
    $headerAboutLabel = setting('header_about_label'.$languageSuffix);
    $headerSubmenus = setting('header_submenus', []);
    if (is_string($headerSubmenus)) {
        $headerSubmenus = json_decode($headerSubmenus, true) ?: [];
    }
    $headerSubmenus = collect(is_array($headerSubmenus) ? $headerSubmenus : [])
        ->filter(fn ($group) => is_array($group) && filled($group['parent'] ?? null))
        ->groupBy('parent');
    $headerLinks = [
        ['key' => 'header_home_label', 'label' => $headerHomeLabel, 'url' => route('home')],
        ['key' => 'header_menu_label', 'label' => $headerMenuLabel, 'url' => \Illuminate\Support\Facades\Route::has('menu.our-menu') ? route('menu.our-menu') : url('/menu/our-menu')],
        ['key' => 'header_promotions_label', 'label' => $headerPromotionsLabel, 'url' => \Illuminate\Support\Facades\Route::has('menu.promotions') ? route('menu.promotions') : url('/menu/promotions')],
        ['key' => 'header_secret_label', 'label' => $headerSecretLabel, 'url' => \Illuminate\Support\Facades\Route::has('secret.tips') ? route('secret.tips') : url('/our-secret/tips')],
        ['key' => 'header_about_label', 'label' => $headerAboutLabel, 'url' => \Illuminate\Support\Facades\Route::has('about') ? route('about') : url('/about')],
    ];
@endphp

<header class="site-header">
    <div class="site-header__inner">
        <a class="site-header__brand" href="{{ route('home') }}" aria-label="{{ $headerBrand }} — {{ $headerHomeLabel }}">
            @if ($headerLogoUrl)
                <img src="{{ $headerLogoUrl }}" alt="{{ $headerBrand }}" width="300" height="78" style="width:300px;height:auto;max-height:82px;object-fit:contain">
            @elseif (filled($headerBrand))
                <span class="site-header__brand-mark" aria-hidden="true">焼</span>
                <span class="site-header__brand-name">{{ $headerBrand }}</span>
            @endif
        </a>

        <div class="site-header__controls">
            <div class="site-header__account">
                @auth
                    <div class="site-account" data-account-dropdown>
                        <button class="site-account__trigger" type="button" aria-expanded="false" aria-controls="siteAccountPanel" aria-label="{{ app()->getLocale() === 'en' ? 'My account' : 'Tài khoản của tôi' }}">
                            <span class="site-account__avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(trim(auth()->user()->name), 0, 1)) ?: '?' }}</span>
                            <span class="site-account__name">{{ auth()->user()->name }}</span>
                            <svg class="site-account__chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div id="siteAccountPanel" class="site-account__panel" hidden>
                            <div class="site-account__identity"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small></div>
                            <a class="site-account__option" href="{{ route('booking.mine') }}">{{ app()->getLocale() === 'en' ? 'My bookings' : 'Đặt bàn của tôi' }}</a>
                            <a class="site-account__option" href="{{ route('password.change') }}">{{ app()->getLocale() === 'en' ? 'Change password' : 'Đổi mật khẩu' }}</a>
                            <form method="POST" action="{{ route('logout') }}">@csrf<button class="site-account__option" type="submit">{{ app()->getLocale() === 'en' ? 'Sign out' : 'Đăng xuất' }}</button></form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}">{{ app()->getLocale() === 'en' ? 'Sign in' : 'Đăng nhập' }}</a>
                    <a href="{{ route('register') }}">{{ app()->getLocale() === 'en' ? 'Register' : 'Đăng ký' }}</a>
                @endauth
            </div>
            <form class="site-header__locale" method="POST" action="{{ route('locale.update') }}" aria-label="{{ app()->getLocale() === 'vi' ? 'Chọn ngôn ngữ' : 'Choose language' }}">
                @csrf
                <button type="submit" name="locale" value="vi" lang="vi" class="{{ app()->getLocale() === 'vi' ? 'is-active' : '' }}" aria-pressed="{{ app()->getLocale() === 'vi' ? 'true' : 'false' }}">VIE</button>
                <button type="submit" name="locale" value="en" lang="en" class="{{ app()->getLocale() === 'en' ? 'is-active' : '' }}" aria-pressed="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}">ENG</button>
            </form>
            <input class="site-header__toggle" type="checkbox" id="siteHeaderToggle" aria-label="{{ app()->getLocale() === 'vi' ? 'Mở menu' : 'Open menu' }}">
            <label class="site-header__toggle-label" for="siteHeaderToggle" aria-hidden="true"><span></span></label>
        </div>

        <nav class="site-header__nav" aria-label="{{ app()->getLocale() === 'vi' ? 'Điều hướng chính' : 'Main navigation' }}">
            @foreach ($headerLinks as $link)
                @if (filled($link['label']))
                    @php
                        $children = $headerSubmenus->get($link['key'], collect())->flatMap(fn ($group) => is_array($group['children'] ?? null) ? $group['children'] : [])
                            ->filter(fn ($child) => is_array($child) && filled($child['label'] ?? null) && filled($child['url'] ?? null))
                            ->map(function (array $child) use ($link): array {
                                $childLabel = mb_strtolower(trim((string) ($child['label'] ?? '')));

                                if ($link['key'] === 'header_menu_label' && $childLabel === 'combo' && \Illuminate\Support\Facades\Route::has('menu.combos')) {
                                    $child['url'] = route('menu.combos');
                                }

                                if ($link['key'] === 'header_menu_label' && in_array($childLabel, ['khuyến mãi', 'promotions'], true) && \Illuminate\Support\Facades\Route::has('menu.promotions')) {
                                    $child['url'] = route('menu.promotions');
                                }

                                if ($link['key'] === 'header_menu_label' && in_array($childLabel, ['dành cho trẻ con', 'for kids', 'for kid'], true) && \Illuminate\Support\Facades\Route::has('menu.for-kid')) {
                                    $child['url'] = route('menu.for-kid');
                                }

                                if (in_array($childLabel, ['công thức', 'recipes', 'recipe'], true) && \Illuminate\Support\Facades\Route::has('secret.recipes')) {
                                    $child['url'] = route('secret.recipes');
                                }

                                if (in_array($childLabel, ['bí kíp', 'bí kíp ăn ngon', 'tips', 'tips & guides'], true) && \Illuminate\Support\Facades\Route::has('secret.tips')) {
                                    $child['url'] = route('secret.tips');
                                }

                                return $child;
                            });
                    @endphp
                    <div class="site-header__item">
                        <a class="site-header__link {{ request()->routeIs('home') && $link['key'] === 'header_home_label' ? 'is-active' : '' }}" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        @if ($children->isNotEmpty())
                            <div class="site-header__submenu">
                                @foreach ($children as $child)
                                    @php $childLabel = app()->getLocale() === 'en' ? ($child['label_en'] ?? null) : null; @endphp
                                    <a class="site-header__submenu-link" href="{{ $child['url'] }}">{{ filled($childLabel) ? $childLabel : $child['label'] }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </nav>
    </div>
</header>
@auth
<script>
    (() => {
        const dropdown = document.querySelector('[data-account-dropdown]');
        if (!dropdown) return;
        const trigger = dropdown.querySelector('.site-account__trigger');
        const panel = dropdown.querySelector('.site-account__panel');
        const setOpen = (open) => {
            trigger.setAttribute('aria-expanded', String(open));
            panel.hidden = !open;
        };
        trigger.addEventListener('click', () => setOpen(panel.hidden));
        document.addEventListener('click', (event) => {
            if (!dropdown.contains(event.target)) setOpen(false);
        });
        dropdown.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !panel.hidden) {
                setOpen(false);
                trigger.focus();
                event.preventDefault();
            }
        });
        dropdown.addEventListener('focusout', (event) => {
            if (!dropdown.contains(event.relatedTarget)) setOpen(false);
        });
    })();
</script>
@endauth

