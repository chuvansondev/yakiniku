@extends('auth.passwords.layout')
@php($isEnglish = app()->getLocale() === 'en')
@section('title', app()->getLocale() === 'en' ? 'Change password' : 'Đổi mật khẩu')
@section('content')
    <p>{{ $isEnglish ? 'Enter your current password and choose a new password with at least 8 characters.' : 'Nhập mật khẩu hiện tại và chọn mật khẩu mới có ít nhất 8 ký tự.' }}</p>
    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        <label for="current_password">{{ $isEnglish ? 'Current password' : 'Mật khẩu hiện tại' }}</label>
        <input id="current_password" type="password" name="current_password" autocomplete="current-password" required autofocus>
        <label for="password">{{ $isEnglish ? 'New password' : 'Mật khẩu mới' }}</label>
        <input id="password" type="password" name="password" autocomplete="new-password" minlength="8" required>
        <label for="password_confirmation">{{ $isEnglish ? 'Confirm new password' : 'Xác nhận mật khẩu mới' }}</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required>
        <button type="submit">{{ $isEnglish ? 'Change password' : 'Đổi mật khẩu' }}</button>
    </form>
@endsection
