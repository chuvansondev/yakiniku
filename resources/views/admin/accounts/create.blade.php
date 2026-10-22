<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tạo tài khoản · {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 40px 18px; background: #f5f3f0; color: #292522; font: 15px/1.5 system-ui, sans-serif; }
        main { width: min(100%, 620px); margin: auto; padding: 30px; border: 1px solid #e5dfd9; border-radius: 12px; background: #fff; }
        h1 { margin: 0 0 24px; font-size: 25px; }
        label { display: block; margin: 17px 0 6px; font-weight: 650; }
        input, select { width: 100%; padding: 11px 12px; border: 1px solid #cfc7c0; border-radius: 7px; font: inherit; }
        button, .back { display: inline-block; margin-top: 23px; padding: 11px 16px; border: 0; border-radius: 7px; background: #8f2527; color: #fff; font: inherit; font-weight: 650; text-decoration: none; cursor: pointer; }
        .back { margin-left: 8px; background: #eee9e5; color: #39322e; }
        .errors { padding: 12px 16px; border-radius: 7px; background: #fff0ef; color: #8e1e1e; }
        .hint { margin: 5px 0 0; color: #716a65; font-size: 13px; }
    </style>
</head>
<body>
<main>
    <h1>Tạo tài khoản người dùng</h1>

    @if ($errors->any())
        <div class="errors" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('admin.accounts.store') }}">
        @csrf
        <label for="name">Họ và tên</label>
        <input id="name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name">

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email">

        <label for="password">Mật khẩu</label>
        <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
        <p class="hint">Tối thiểu 8 ký tự.</p>

        <label for="password_confirmation">Xác nhận mật khẩu</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">

        <label for="role_id">Vai trò</label>
        <select id="role_id" name="role_id">
            <option value="">Không gán vai trò</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
            @endforeach
        </select>

        <button type="submit">Tạo tài khoản</button>
        <a class="back" href="{{ route('admin.accounts.index') }}">Quay lại</a>
    </form>
</main>
</body>
</html>
