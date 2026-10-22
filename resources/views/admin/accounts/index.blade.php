<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý tài khoản · {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 40px 18px; background: #f5f3f0; color: #292522; font: 15px/1.5 system-ui, sans-serif; }
        main { width: min(100%, 1000px); margin: auto; padding: 30px; border: 1px solid #e5dfd9; border-radius: 12px; background: #fff; }
        header { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 24px; }
        h1 { margin: 0; font-size: 25px; }
        a.button { padding: 10px 14px; border-radius: 7px; background: #8f2527; color: #fff; font-weight: 650; text-decoration: none; }
        form.search { display: flex; gap: 8px; margin-bottom: 18px; }
        input { min-width: 0; flex: 1; padding: 10px 12px; border: 1px solid #cfc7c0; border-radius: 7px; font: inherit; }
        button { padding: 10px 14px; border: 0; border-radius: 7px; background: #eee9e5; font: inherit; cursor: pointer; }
        .notice { margin-bottom: 16px; padding: 12px; border-radius: 7px; background: #edf8ed; color: #225c26; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #eee9e5; white-space: nowrap; }
        th { color: #716a65; font-size: 13px; }
        nav { margin-top: 20px; }
        @media (max-width: 600px) { main { padding: 20px 14px; } header { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
<main>
    <header>
        <h1>Quản lý tài khoản</h1>
        @if (auth()->user()->hasPermission('admin.accounts.create'))
            <a class="button" href="{{ route('admin.accounts.create') }}">Tạo tài khoản</a>
        @endif
    </header>

    @if (session('success')) <div class="notice" role="status">{{ session('success') }}</div> @endif

    <form class="search" method="GET" action="{{ route('admin.accounts.index') }}">
        <input name="search" value="{{ request('search') }}" placeholder="Tìm theo tên hoặc email" aria-label="Tìm tài khoản">
        <button type="submit">Tìm</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Tên</th><th>Email</th><th>Vai trò</th><th>Trạng thái</th><th>Ngày tạo</th></tr></thead>
            <tbody>
            @forelse ($accounts as $account)
                <tr>
                    <td>{{ $account->name }}</td>
                    <td>{{ $account->email }}</td>
                    <td>{{ $account->role?->name ?? '—' }}</td>
                    <td>{{ $account->is_locked ? 'Đã khóa' : 'Hoạt động' }}</td>
                    <td>{{ $account->created_at?->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Chưa có tài khoản phù hợp.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <nav aria-label="Phân trang">{{ $accounts->links() }}</nav>
</main>
</body>
</html>
