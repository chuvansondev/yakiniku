@extends('auth.passwords.layout')
@php($isEnglish = app()->getLocale() === 'en')
@section('title', app()->getLocale() === 'en' ? 'Reset password' : 'Đặt lại mật khẩu')
@section('content')
    @if ($validLink)
    <p>{{ $isEnglish ? 'Choose a new password with at least 8 characters.' : 'Chọn mật khẩu mới có ít nhất 8 ký tự.' }}</p>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" autocomplete="email" required>
        <label for="password">{{ $isEnglish ? 'New password' : 'Mật khẩu mới' }}</label>
        <input id="password" type="password" name="password" autocomplete="new-password" minlength="8" required>
        <label for="password_confirmation">{{ $isEnglish ? 'Confirm new password' : 'Xác nhận mật khẩu mới' }}</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required>
        <button type="submit">{{ $isEnglish ? 'Reset password' : 'Đặt lại mật khẩu' }}</button>
    </form>
    @else
        <div class="auth-errors" role="alert">{{ __('The password reset link is invalid or has expired. Please request a new link.') }}</div>
    @endif
    <p><a href="{{ route('password.request') }}">{{ $isEnglish ? 'Request a new link' : 'Yêu cầu liên kết mới' }}</a></p>
@endsection
