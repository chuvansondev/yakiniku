@php($isEnglish = app()->getLocale() === 'en')
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    @include('frontend.partials.assets')
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isEnglish ? 'Sign in' : 'Đăng nhập' }} | {{ config('app.name') }}</title>
    @include('frontend.partials.font-preloads')
    <style>
        @font-face{font-family:'UTM BryantLG';src:url('{{ asset('fonts/UTM%20BryantLG.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        @font-face{font-family:'UTM Pacific';src:url('{{ asset('fonts/UTM-Pacific-Standard.woff2') }}') format('woff2');font-weight:400;font-display:swap}
        *{box-sizing:border-box}body{margin:0;background:#faf7f3;color:#332a26;font:16px/1.55 'UTM BryantLG',Arial,sans-serif}.auth-main{width:min(100% - 32px,1160px);min-height:calc(100vh - 220px);margin:auto;padding:56px 0}.card{width:min(100%,520px);margin:0 auto;padding:34px 38px;background:#fff;border:1px solid #eadfd5;box-shadow:0 12px 34px #38271c12}h1{margin:0 0 8px;color:#96262a;font:36px/1.2 'UTM Pacific',Georgia,serif}.intro{margin:0 0 22px;color:#75645a}label{display:block;margin:15px 0 5px;font-weight:700}input:not([type=checkbox]){width:100%;min-height:43px;padding:10px 12px;border:1px solid #d6ccc3;border-radius:3px;background:#fff;color:#332a26;font:inherit}input:focus{outline:2px solid #a4212730;border-color:#a42127}button,.link{display:inline-block;margin-top:20px;padding:11px 16px;border:0;border-radius:3px;background:#a42127;color:white;font:inherit;font-weight:700;text-decoration:none;cursor:pointer}.link{margin-left:8px;background:#f1ece8;color:#493b35}.errors{padding:10px 14px;background:#fff0ee;color:#a42127}.status{padding:10px 14px;background:#eef7ed;color:#28602d}.remember{display:flex;align-items:center;gap:8px;margin-top:13px}.remember input{accent-color:#a42127}
        @media(max-width:600px){.auth-main{padding:28px 0;min-height:calc(100vh - 170px)}.card{padding:24px 20px}h1{font-size:31px}}
    </style>
</head>
<body>
@include('frontend.partials.header')
<main class="auth-main"><section class="card">
    <h1>{{ $isEnglish ? 'Welcome back' : 'Đăng nhập tài khoản' }}</h1>
    <p class="intro">{{ $isEnglish ? 'Sign in to view your booking history.' : 'Đăng nhập để xem lịch sử và trạng thái đặt bàn.' }}</p>
    @if ($errors->any())<div class="errors" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @if (session('status'))<div class="status" role="status">{{ session('status') }}</div>@endif
    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
        <label for="password">{{ $isEnglish ? 'Password' : 'Mật khẩu' }}</label><input id="password" type="password" name="password" autocomplete="current-password" required>
        <label class="remember"><input type="checkbox" name="remember" value="1">{{ $isEnglish ? 'Remember me' : 'Ghi nhớ đăng nhập' }}</label>
        <button type="submit">{{ $isEnglish ? 'Sign in' : 'Đăng nhập' }}</button><a class="link" href="{{ route('register') }}">{{ $isEnglish ? 'Create account' : 'Tạo tài khoản' }}</a>
    </form>
    <p><a href="{{ route('password.request') }}" style="color:#96262a">{{ $isEnglish ? 'Forgot your password?' : 'Quên mật khẩu?' }}</a></p>
</section></main>
@include('frontend.partials.footer')
</body></html>
