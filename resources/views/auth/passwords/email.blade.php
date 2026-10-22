@extends('auth.passwords.layout')
@php($isEnglish = app()->getLocale() === 'en')
@section('title', app()->getLocale() === 'en' ? 'Forgot password' : 'Quên mật khẩu')
@section('content')
    <p>{{ $isEnglish ? 'Enter your account email to receive a password reset link.' : 'Nhập email tài khoản để nhận liên kết đặt lại mật khẩu.' }}</p>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
        <button type="submit">{{ $isEnglish ? 'Send reset link' : 'Gửi liên kết đặt lại mật khẩu' }}</button>
    </form>
@endsection
