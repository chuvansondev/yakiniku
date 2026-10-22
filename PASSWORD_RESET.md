# Đặt lại mật khẩu

## Đổi mật khẩu khi đã đăng nhập

- Chọn **Đổi mật khẩu** trên thanh điều hướng hoặc truy cập `/change-password`.
- Nhập mật khẩu hiện tại, mật khẩu mới ít nhất 8 ký tự và xác nhận mật khẩu mới.
- Đổi thành công vẫn giữ phiên hiện tại; các phiên có phiên bản đăng nhập cũ bị từ chối khi kiểm tra `active`. Token reset cũ cũng bị xóa.

## Quên mật khẩu

- Truy cập `/forgot-password` hoặc chọn **Quên mật khẩu?** tại trang đăng nhập.
- Mở link `/reset-password/{token}?email=...` trong email, nhập và xác nhận mật khẩu mới (ít nhất 8 ký tự), rồi đăng nhập. Token được điền tự động từ link.
- Link sai, hết hạn, đã dùng hoặc thiếu email sẽ hiện thông báo và liên kết yêu cầu email reset mới; không hiển thị form đổi mật khẩu.
- Email chứa liên kết dùng một lần, có hiệu lực 60 phút. Mỗi địa chỉ chỉ được tạo token mới sau 60 giây.
- Email được gửi qua queue. Chạy `php artisan queue:work --tries=3 --timeout=60` khi dùng `QUEUE_CONNECTION=database`.
- Mặc định `MAIL_MAILER=log`: nội dung email được ghi trong `storage/logs/laravel.log`, không gửi tới hộp thư.
- Để gửi email thật, cấu hình `.env`: `MAIL_MAILER=smtp`, `MAIL_SCHEME`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` theo nhà cung cấp SMTP. Đặt `APP_URL` thành địa chỉ website truy cập được từ email.
- Sau khi đổi cấu hình, chạy `php artisan config:clear` và `php artisan queue:restart`.
- Reset thành công sẽ đổi mật khẩu, vô hiệu hóa token reset và cập nhật phiên đăng nhập của tài khoản.
