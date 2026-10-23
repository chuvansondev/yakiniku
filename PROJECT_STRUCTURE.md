# Cấu trúc dự án

- `app/Http/Controllers`: trang khách hàng, xác thực và endpoint đặt bàn/đăng ký ưu đãi.
- `app/Http/Controllers/Admin/AccountController.php`: trang tài khoản `/accounts` còn được sử dụng.
- `app/Filament`: giao diện quản trị chính; resource gồm schemas, tables và pages.
- `app/Http/Requests`: kiểm tra dữ liệu request còn hoạt động.
- `app/Services`: xử lý nghiệp vụ đang được sử dụng.
- `app/Repositories`: truy cập dữ liệu; tất cả contract đặt trong `Contracts` và dùng hậu tố `Contract`.
- `app/Models`: model, `Concerns` chứa trait và `Pivots` chứa model bảng liên kết.
- `resources/views/frontend`: home, about, menu, recipes, tips, bookings và partial dùng chung.
- `resources/views/auth`: đăng nhập, đăng ký và các trang mật khẩu/xác thực.
- `resources/views/filament`: view tùy chỉnh cho Filament.
- `resources/views/admin/accounts`: view của `/accounts`.
- `resources/views/emails`: email giao dịch.
- `resources/css`, `resources/scss`, `resources/js`: mã nguồn asset được Vite biên dịch.
- `public`: entrypoint và asset tĩnh. Build và liên kết storage không đưa vào Git.
- `database`: migration, factory và seeder.
- `tests/Feature`: kiểm tra trang công khai, repository binding và luồng xác thực.
- `storage`, `bootstrap/cache`: dữ liệu runtime; chỉ giữ file `.gitignore` trong Git.

## Migration cũ

Giữ nguyên tên `2026_10_15_062423_create_menu-_categories_table.php`. Laravel dùng tên migration để ghi nhận lịch sử; đổi tên có thể khiến migration đã chạy bị nhận là migration mới. Các migration mới dùng snake_case đúng quy ước.

## Mã cũ đã dọn

Các controller/request quản trị không được nối route và thiếu view, các service đi kèm không còn nơi sử dụng, cùng action tra cứu đặt bàn không được nối route đã được loại bỏ. Quản trị tiếp tục qua Filament, danh sách đặt bàn cá nhân tiếp tục qua `/my-bookings`.
