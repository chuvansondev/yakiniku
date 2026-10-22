@php($isEnglish = app()->getLocale() === 'en')
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    @include('frontend.partials.assets')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isEnglish ? 'Verify your email' : 'Xác minh email' }} | {{ config('app.name') }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        *{box-sizing:border-box}body{margin:0;background:#faf7f3;color:#332a26;font:16px/1.55 'UTM BryantLG',Arial,sans-serif}.auth-main{width:min(100% - 32px,1160px);min-height:calc(100vh - 220px);margin:auto;padding:56px 0}.card{width:min(100%,520px);margin:0 auto;padding:34px 38px;background:#fff;border:1px solid #eadfd5;box-shadow:0 12px 34px #38271c12}h1{margin:0 0 8px;color:#96262a;font:36px/1.2 'UTM Pacific',Georgia,serif}.intro{margin:0 0 22px;color:#75645a}.status{padding:10px 14px;background:#eef7ed;color:#28602d}button{display:inline-block;margin-top:20px;padding:11px 16px;border:0;border-radius:3px;background:#a42127;color:white;font:inherit;font-weight:700;cursor:pointer}@media(max-width:600px){.auth-main{padding:28px 0;min-height:calc(100vh - 170px)}.card{padding:24px 20px}h1{font-size:31px}}
    </style>
</head>
<body>
@include('frontend.partials.header')
    <main class="auth-main">
        <section class="card">
            <h1>{{ $isEnglish ? 'Verify your email address' : 'Xác minh địa chỉ email' }}</h1>
            <p class="intro">{{ $isEnglish ? 'Please use the link we sent to your email. Bookings previously made with this email will appear in your account after verification.' : 'Vui lòng mở liên kết chúng tôi đã gửi đến email của bạn. Các đặt bàn trước đây bằng email này sẽ xuất hiện sau khi xác minh.' }}</p>

            @if (session('status'))
                <p class="status" role="status">{{ session('status') }}</p>
            @endif

            <form method="post" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit">{{ $isEnglish ? 'Resend verification email' : 'Gửi lại email xác minh' }}</button>
            </form>
        </section>
    </main>
@include('frontend.partials.footer')
</body>
</html>
