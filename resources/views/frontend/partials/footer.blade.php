@php
    $languageSuffix = app()->getLocale() === 'en' ? '_en' : '';
    $footerName = setting('footer_brand_name'.$languageSuffix);
    $footerLogo = setting('footer_logo_url');
    $footerLogoUrl = filled($footerLogo)
        ? (\Illuminate\Support\Str::startsWith($footerLogo, ['http://', 'https://', '/'])
            ? $footerLogo
            : asset('storage/'.ltrim($footerLogo, '/')))
        : null;
    $footerAddress = setting('footer_address'.$languageSuffix);
    $footerDescription = setting('footer_description'.$languageSuffix);
    $footerHours = setting('footer_hours'.$languageSuffix);
    $footerPhone = setting('hotline'.$languageSuffix);
    $footerPhone2 = setting('hotline_2');
    $footerEmail = setting('email');
    $footerCompanyName = setting('footer_company_name'.$languageSuffix);
    $footerCompanyAddress = setting('footer_company_address'.$languageSuffix);
    $footerSubscribeText = setting('footer_subscribe_text'.$languageSuffix);
    $footerTopicLinesVi = filled(setting('footer_topics'))
        ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', setting('footer_topics')))))
        : [];
    $footerTopicLinesEn = filled(setting('footer_topics_en'))
        ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', setting('footer_topics_en')))))
        : [];
    $displayTopicLines = app()->getLocale() === 'en' ? $footerTopicLinesEn : $footerTopicLinesVi;
    $footerTopics = array_map(function (string $line, int $index) use ($footerTopicLinesVi): array {
        [$label] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
        [$vietnameseLabel, $vietnameseUrl] = array_pad(array_map('trim', explode('|', $footerTopicLinesVi[$index] ?? $line, 2)), 2, '');
        $slug = \Illuminate\Support\Str::slug($vietnameseLabel);
        $tagUrl = str_starts_with($vietnameseUrl, 'https://ussinavietnam.vn/')
            ? $vietnameseUrl
            : 'https://ussinavietnam.vn/tag/'.$slug.'/';

        return ['label' => $label, 'url' => $tagUrl];
    }, $displayTopicLines, array_keys($displayTopicLines));
    $facebookUrl = setting('facebook_url');
    $youtubeUrl = setting('youtube_url');
    $zaloUrl = setting('zalo_url');
    $tripadvisorUrl = setting('tripadvisor_url');
    $googleBusinessUrl = setting('google_business_url');
@endphp

<style>
    .site-footer {
        --footer-bg: #101419;
        --footer-panel: #171d23;
        --footer-text: #a5b0bd;
        --footer-title: #f6f7f8;
        --footer-accent: #da3038;
        --footer-line: rgba(255, 255, 255, .13);
        width: 100%;
        background: var(--footer-bg);
        color: var(--footer-text);
        font-family: 'UTM BryantLG';
    }
    .site-footer *, .site-footer *::before, .site-footer *::after { box-sizing: border-box; }
    .site-footer__main { width: min(100% - 48px, 1240px); margin-inline: auto; padding: 56px 0 46px; }
    .site-footer__grid { display: grid; grid-template-columns: 1.08fr 1.18fr .92fr; gap: clamp(30px, 5vw, 76px); }
    .site-footer__title { margin: 0 0 18px; color: var(--footer-title); font-size: 14px; font-weight: 750; letter-spacing: .025em; line-height: 1.5; text-transform: uppercase; }
    .site-footer__brand-logo { display: block; width: auto; max-width: 190px; max-height: 72px; margin: -6px 0 18px; object-fit: contain; object-position: left center; }
    .site-footer__copy { margin: 0 0 14px; color: var(--footer-text); font-size: 13px; line-height: 1.9; }
    .site-footer__contact { display: grid; gap: 7px; margin: 18px 0 20px; padding: 0; list-style: none; }
    .site-footer__contact li { display: flex; gap: 10px; color: #bdc6d0; font-size: 13px; line-height: 1.7; overflow-wrap: anywhere; }
    .site-footer__contact-label { width: 18px; flex: 0 0 18px; color: #fff; font-weight: 800; }
    .site-footer__contact a, .site-footer a { color: inherit; text-decoration: none; }
    .site-footer__contact a:hover, .site-footer a:hover { color: #fff; }
    .site-footer__socials { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; margin: 18px 0; }
    .site-footer__social { width: 50px; height: 50px; display: inline-grid; place-items: center; overflow: hidden; padding: 0; border: 1px solid var(--footer-line); border-radius: 50%; background: #000; color: #1d252d !important; font-size: 18px; font-weight: 800; }
    .site-footer__social img { display: block; width: 100%; height: 100%; border-radius: inherit; object-fit: cover; }
    .site-footer__social:hover { background: #000; color: #1d252d !important; }
    .site-footer__notice { display: inline-flex; align-items: center; gap: 9px; margin-top: 4px; padding: 8px 12px; border: 1px solid rgba(68, 161, 215, .55); border-radius: 3px; background: #086ca3; color: #fff; font-size: 11px; font-weight: 750; line-height: 1.35; }
    .site-footer__notice-mark { width: 28px; height: 28px; display: grid; place-items: center; border-radius: 50%; background: #fff; color: #1682b7; font-size: 19px; }
    .site-footer__signup { margin: 0 0 23px; padding-bottom: 21px; border-bottom: 1px solid var(--footer-line); }
    .site-footer__button { display: inline-flex; min-width: 145px; min-height: 42px; align-items: center; justify-content: space-between; gap: 22px; margin-top: 4px; padding: 0 14px; border: 1px solid rgba(255,255,255,.55); color: #fff !important; font-size: 12px; font-weight: 650; letter-spacing: .04em; text-transform: uppercase; transition: background .18s ease, border-color .18s ease; }
    .site-footer__button:hover { border-color: var(--footer-accent); background: var(--footer-accent); }
    .lead-modal[hidden] { display:none; }
    .lead-modal { position:fixed; inset:0; z-index:1000; display:grid; place-items:center; padding:18px; background:rgba(0,0,0,.78); }
    .lead-modal__panel { position:relative; width:min(100%,464px); padding:20px; border:1px solid #545454; background:rgba(20,20,20,.97); box-shadow:0 18px 55px #0008; }
    .lead-modal__grid { display:grid; grid-template-columns:1fr 1fr; gap:14px 8px; }
    .lead-modal__field { min-width:0; }
    .lead-modal__field input,.lead-modal__field select { width:100%; height:45px; padding:0 10px; border:1px solid #666; border-radius:0; background:#17191c; color:#eee; font:14px 'UTM BryantLG',Arial,sans-serif; }
    .lead-modal__field input:focus,.lead-modal__field select:focus { outline:1px solid #a42127; border-color:#a42127; }
    .lead-modal__submit { display:block; min-width:125px; margin:16px auto 0; padding:12px 18px; border:0; background:#a42127; color:#fff; font:700 15px 'UTM BryantLG',Arial,sans-serif; cursor:pointer; }
    .lead-modal__close { position:absolute; right:12px; bottom:10px; padding:0; border:0; background:none; color:#aaa; font-size:24px; cursor:pointer; }
    .lead-modal__message { grid-column:1/-1; min-height:18px; margin:0; color:#f0b8b5; font-size:13px; }
    .lead-modal__message.is-success { color:#a7d7aa; }
    body.has-lead-modal { overflow:hidden; }
    .lead-sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
    .site-footer__legal { margin: 0; color: var(--footer-text); font-size: 12px; line-height: 1.95; }
    .site-footer__topics { display: flex; flex-wrap: wrap; gap: 8px; margin: 0 0 20px; padding: 0; list-style: none; }
    .site-footer__topics a { color: var(--footer-text); font-size: 12px; line-height: 1.65; }
    .site-footer__topics a::after { content: ','; }
    .site-footer__topics li:last-child a::after { content: '…'; }
    .site-footer .site-footer__facebook { color-scheme:only light; width:100%; max-width:320px; overflow:hidden; border:1px solid #d5d9dd; background:#fff!important; color:#1c1e21; font-family:Arial,sans-serif; }
    .site-footer .site-footer__facebook-header { display:flex; min-height:68px; align-items:center; gap:9px; padding:8px; background-color:#fff!important; color:#1c1e21!important; text-decoration:none; }
    .site-footer__facebook-avatar { width:38px; height:38px; flex:0 0 38px; object-fit:cover; }
    .site-footer__facebook-meta { display:grid; min-width:0; gap:3px; }
    .site-footer__facebook-name { overflow:hidden; color:#365899; font-size:13px; font-weight:700; text-overflow:ellipsis; white-space:nowrap; }
    .site-footer__facebook-followers { color:#4b4f56; font-size:10px; }
    .site-footer .site-footer__facebook-actions { display:flex; height:34px; align-items:center; justify-content:space-between; padding:0 7px; border-top:1px solid #e5e7eb; background-color:#fff!important; }
    .site-footer .site-footer__facebook-actions a { display:inline-flex; align-items:center; gap:4px; padding:4px 6px; border:1px solid #d5d9dd; background-color:#fff!important; color:#4b4f56!important; font-size:10px; line-height:1; text-decoration:none; }
    .site-footer__facebook-actions a:hover { background:#f5f6f7; }
    .site-footer__facebook-f { color:#4267b2; font-size:12px; font-weight:700; }
    .site-footer__bottom { border-top: 1px solid var(--footer-line); }
    .site-footer__copyright { width: min(100% - 48px, 1240px); margin-inline: auto; padding: 16px 0 20px; color: #87929e; font-size: 11px; }
    @media (max-width: 900px) { .site-footer__grid { grid-template-columns: 1fr 1fr; } .site-footer__column:last-child { grid-column: 1 / -1; } }
    @media (max-width: 620px) { .site-footer__main { width: calc(100% - 36px); padding: 42px 0 34px; } .site-footer__grid { grid-template-columns: 1fr; gap: 34px; } .site-footer__column:last-child { grid-column: auto; } .site-footer__copyright { width: calc(100% - 36px); } }
</style>

<footer class="site-footer">
    <div class="site-footer__main">
        <div class="site-footer__grid">
            <section class="site-footer__column" aria-labelledby="footer-restaurant-title">
                @if (filled($footerName))<h2 class="site-footer__title" id="footer-restaurant-title">{{ $footerName }}</h2>@endif
                @if ($footerLogoUrl)<img class="site-footer__brand-logo" src="{{ $footerLogoUrl }}" alt="{{ $footerName ?: config('app.name') }}" loading="lazy">@endif
                @if (filled($footerDescription))<p class="site-footer__copy">{{ $footerDescription }}</p>@endif
                @if ($footerAddress)
                    <p class="site-footer__copy">{{ $footerAddress }}</p>
                @endif
                @if ($footerHours)<p class="site-footer__copy">{{ $footerHours }}</p>@endif
                <ul class="site-footer__contact">
                    @if ($footerPhone)
                        <li><span class="site-footer__contact-label" aria-hidden="true">T</span><a href="tel:{{ preg_replace('/[^0-9+]/', '', $footerPhone) }}">{{ $footerPhone }}</a></li>
                    @endif
                    @if ($footerPhone2)
                        <li><span class="site-footer__contact-label" aria-hidden="true">T</span><a href="tel:{{ preg_replace('/[^0-9+]/', '', $footerPhone2) }}">{{ $footerPhone2 }}</a></li>
                    @endif
                    @if (filled($footerEmail))
                        <li><span class="site-footer__contact-label" aria-hidden="true">E</span><span>Email: <a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a></span></li>
                    @endif
                </ul>
                <div class="site-footer__socials" aria-label="{{ app()->getLocale() === 'vi' ? 'Mạng xã hội' : 'Social media' }}">
                    @if ($facebookUrl)<a class="site-footer__social" href="{{ $facebookUrl }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><img src="{{ asset('yakiniku/logo-facebook.png') }}" alt=""></a>@endif
                    @if ($tripadvisorUrl)<a class="site-footer__social" href="{{ $tripadvisorUrl }}" aria-label="Tripadvisor" title="{{ app()->getLocale() === 'vi' ? 'Đánh giá trên Tripadvisor' : 'Review us on Tripadvisor' }}" target="_blank" rel="noopener noreferrer"><img src="{{ asset('yakiniku/tripadvisor-icon.png') }}" alt=""></a>@endif
                    @if ($googleBusinessUrl)<a class="site-footer__social" href="{{ $googleBusinessUrl }}" aria-label="Google Business" title="{{ app()->getLocale() === 'vi' ? 'Tìm Ussina trên Google' : 'Find Ussina on Google' }}" target="_blank" rel="noopener noreferrer"><img src="{{ asset('yakiniku/gg-my-business.png') }}" alt=""></a>@endif
                </div>
                @if (file_exists(public_path('yakiniku/bo-cong-thuong.png')))
                    <img src="{{ asset('yakiniku/bo-cong-thuong.png') }}" alt="{{ app()->getLocale() === 'vi' ? 'Thông báo Bộ Công Thương' : 'Ministry of Industry and Trade registration' }}" style="width:150px;height:auto;margin-top:8px">
                @endif
            </section>

            <section class="site-footer__column" aria-labelledby="footer-signup-title">
                <div class="site-footer__signup">
                    <h2 class="site-footer__title" id="footer-signup-title">{{ app()->getLocale() === 'vi' ? 'Đăng ký nhận ưu đãi' : 'Sign up for special offers' }}</h2>
                    @if (filled($footerSubscribeText))<p class="site-footer__copy">{{ $footerSubscribeText }}</p>@endif
                    <a class="site-footer__button" href="#lead-signup" data-open-lead-modal>
                        <span>{{ app()->getLocale() === 'vi' ? 'Đăng ký' : 'Subscribe' }}</span><span aria-hidden="true">›</span>
                    </a>
                </div>
                @if (filled($footerCompanyName))<h2 class="site-footer__title">{{ $footerCompanyName }}</h2>@endif
                @if (filled($footerCompanyAddress))<p class="site-footer__legal">{{ $footerCompanyAddress }}</p>@endif
            </section>

            @if (count($footerTopics))
            <section class="site-footer__column" aria-labelledby="footer-topics-title">
                <h2 class="site-footer__title" id="footer-topics-title">{{ app()->getLocale() === 'vi' ? 'Chủ đề nổi bật' : 'Popular topics' }}</h2>
                <ul class="site-footer__topics">
                    @foreach ($footerTopics as $topic)
                        <li><a href="{{ $topic['url'] }}" target="_blank" rel="noopener noreferrer">{{ $topic['label'] }}</a></li>
                    @endforeach
                </ul>
                @if (filled($facebookUrl))
                    <div class="site-footer__facebook" style="color-scheme:only light;background:#fff!important;color:#1c1e21!important;">
                        <a class="site-footer__facebook-header" href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" style="background:#fff!important;color:#1c1e21!important;">
                            <img class="site-footer__facebook-avatar" src="{{ asset('yakiniku/usina.jpg') }}" alt="">
                            <span class="site-footer__facebook-meta">
                                <span class="site-footer__facebook-name">Ussina Sky 77 - Aging Beef &amp; Bar</span>
                                <span class="site-footer__facebook-followers">37.782 người theo dõi</span>
                            </span>
                        </a>
                        <div class="site-footer__facebook-actions" style="background:#fff!important;">
                            <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" style="background:#fff!important;color:#4b4f56!important;"><span class="site-footer__facebook-f">f</span>{{ app()->getLocale() === 'en' ? 'Follow Page' : 'Theo dõi Trang' }}</a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($facebookUrl) }}" target="_blank" rel="noopener noreferrer" style="background:#fff!important;color:#4b4f56!important;">↗ {{ app()->getLocale() === 'en' ? 'Share' : 'Chia sẻ' }}</a>
                        </div>
                    </div>
                @endif
            </section>
            @endif
        </div>
    </div>
    <div class="site-footer__bottom">
        <div class="site-footer__copyright">© {{ date('Y') }} {{ $footerName }}. {{ app()->getLocale() === 'vi' ? 'Managed by Sagi.' : 'Managed by Sagi.' }}</div>
    </div>
</footer>

<div class="lead-modal" id="lead-signup" hidden>
    <section class="lead-modal__panel" role="dialog" aria-modal="true" aria-labelledby="lead-modal-title">
        <h2 class="site-footer__title" id="lead-modal-title">{{ app()->getLocale() === 'vi' ? 'ĐĂNG KÝ NHẬN ƯU ĐÃI' : 'SIGN UP FOR SPECIAL OFFERS' }}</h2>
        <form id="leadSignupForm" method="POST" action="{{ route('leads.store') }}">
            @csrf
            <div class="lead-modal__grid">
                <div class="lead-modal__field"><label class="lead-sr-only" for="leadSalutation">{{ app()->getLocale() === 'vi' ? 'Danh xưng' : 'Title' }}</label><select id="leadSalutation" name="salutation" required><option value="">{{ app()->getLocale() === 'vi' ? 'Chọn danh xưng' : 'Choose title' }}</option><option value="Ông">{{ app()->getLocale() === 'vi' ? 'Ông' : 'Mr.' }}</option><option value="Bà">{{ app()->getLocale() === 'vi' ? 'Bà' : 'Ms.' }}</option></select></div>
                <div class="lead-modal__field"><label class="lead-sr-only" for="leadName">{{ app()->getLocale() === 'vi' ? 'Họ và tên' : 'Full name' }}</label><input id="leadName" name="name" type="text" placeholder="{{ app()->getLocale() === 'vi' ? 'Họ và tên*' : 'Full name*' }}" required maxlength="255"></div>
                <div class="lead-modal__field"><label class="lead-sr-only" for="leadPhone">{{ app()->getLocale() === 'vi' ? 'Số điện thoại' : 'Phone number' }}</label><input id="leadPhone" name="phone" type="tel" placeholder="{{ app()->getLocale() === 'vi' ? 'Số điện thoại' : 'Phone number' }}" required maxlength="30"></div>
                <div class="lead-modal__field"><label class="lead-sr-only" for="leadEmail">Email</label><input id="leadEmail" name="email" type="email" placeholder="Email" required maxlength="255"></div>
                <p class="lead-modal__message" id="leadModalMessage" aria-live="polite"></p>
            </div>
            <button class="lead-modal__submit" type="submit">{{ app()->getLocale() === 'vi' ? 'ĐĂNG KÝ' : 'SUBSCRIBE' }}</button>
        </form>
        <button class="lead-modal__close" type="button" data-close-lead-modal aria-label="{{ app()->getLocale() === 'vi' ? 'Đóng' : 'Close' }}">×</button>
    </section>
</div>
<script>
    (() => {
        const modal = document.getElementById('lead-signup');
        const form = document.getElementById('leadSignupForm');
        if (!modal || !form) return;
        const message = document.getElementById('leadModalMessage');
        const close = () => { modal.hidden = true; document.body.classList.remove('has-lead-modal'); };
        document.querySelectorAll('[data-open-lead-modal]').forEach((trigger) => trigger.addEventListener('click', (event) => {
            event.preventDefault(); modal.hidden = false; document.body.classList.add('has-lead-modal'); form.querySelector('select').focus();
        }));
        modal.querySelectorAll('[data-close-lead-modal]').forEach((button) => button.addEventListener('click', close));
        modal.addEventListener('click', (event) => { if (event.target === modal) close(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !modal.hidden) close(); });
        form.addEventListener('submit', async (event) => {
            event.preventDefault(); message.textContent = ''; message.classList.remove('is-success');
            const submit = form.querySelector('[type="submit"]'); submit.disabled = true;
            try {
                const response = await fetch(form.action, { method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body:new FormData(form) });
                const payload = await response.json();
                if (!response.ok) throw new Error(payload.message || Object.values(payload.errors || {}).flat()[0] || '{{ app()->getLocale() === 'vi' ? 'Vui lòng kiểm tra lại thông tin.' : 'Please check your details.' }}');
                message.textContent = payload.message || '{{ app()->getLocale() === 'vi' ? 'Đăng ký thành công!' : 'Thank you for subscribing!' }}';
                message.classList.add('is-success'); form.reset(); window.setTimeout(close, 1800);
            } catch (error) { message.textContent = error.message || '{{ app()->getLocale() === 'vi' ? 'Không thể gửi đăng ký. Vui lòng thử lại.' : 'Could not submit. Please try again.' }}'; }
            finally { submit.disabled = false; }
        });
    })();
</script>
