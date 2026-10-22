@php($isEnglish = app()->getLocale() === 'en')
<!doctype html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>
    @include('frontend.partials.assets')
    @include('frontend.partials.font-preloads')
    <style>
        *{box-sizing:border-box}body{margin:0;background:#faf7f3;color:#332a26;font:16px/1.55 Arial,sans-serif}.auth-main{width:min(100% - 32px,1160px);min-height:calc(100vh - 220px);margin:auto;padding:56px 0}.auth-card{width:min(100%,520px);margin:auto;padding:34px 38px;background:white;border:1px solid #eadfd5;box-shadow:0 12px 34px #38271c12}.auth-card h1{margin:0 0 8px;color:#96262a;font-size:32px}.auth-card p{color:#75645a}.auth-card label{display:block;margin:15px 0 5px;font-weight:700}.auth-card input{width:100%;min-height:43px;padding:10px 12px;border:1px solid #d6ccc3;border-radius:3px;font:inherit}.auth-card input:focus{outline:2px solid #a4212730;border-color:#a42127}.auth-card button{margin-top:20px;padding:11px 16px;border:0;border-radius:3px;background:#a42127;color:white;font:inherit;font-weight:700;cursor:pointer}.auth-card a{color:#96262a}.auth-errors{padding:10px 14px;background:#fff0ee;color:#a42127}.auth-status{padding:10px 14px;background:#eef7ed;color:#28602d}@media(max-width:600px){.auth-main{padding:28px 0}.auth-card{padding:24px 20px}}
    </style>
</head>
<body>
@include('frontend.partials.header')
<main class="auth-main"><section class="auth-card">
    <h1>@yield('title')</h1>
    @if ($errors->any())
        <div class="auth-errors" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @if (session('status'))<div class="auth-status" role="status">{{ session('status') }}</div>@endif
    @yield('content')
    @auth
        <p><a href="{{ route('home') }}">{{ $isEnglish ? 'Back to home' : 'Về trang chủ' }}</a></p>
    @else
        <p><a href="{{ route('login') }}">{{ $isEnglish ? 'Back to sign in' : 'Quay lại đăng nhập' }}</a></p>
    @endauth
</section></main>
@include('frontend.partials.footer')
</body>
</html>
