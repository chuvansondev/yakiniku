# Quy ước đặt tên

- Class, trait, enum: PascalCase; tên file và namespace phải khớp chính xác chữ hoa/thường (PSR-4).
- Model và các lớp gắn với model dùng danh từ số ít: KidItem, KidItemController, KidItemRequest, KidItemType, KidFoodCategory.
- Method, biến PHP/JavaScript và biến truyền vào view: camelCase, mô tả mục đích; ví dụ filterUrl, recipeBrand, tipsBrand.
- Global helper dùng snake_case theo quy ước hiện có: localized_text, localized_setting, localized_price. Giữ các tên lifecycle do Laravel/Filament yêu cầu.
- Hằng số: UPPER_SNAKE_CASE. Cột database, khóa request và cấu hình: snake_case.
- Route name: phân cấp bằng dấu chấm, đoạn nhiều từ dùng kebab-case; ví dụ menu.for-kids. URL công khai giữ tương thích khi đổi tên route nội bộ.
- Blade view, partial và component: tên file kebab-case, tham chiếu thư mục bằng dấu chấm. Component Blade mới dùng thẻ kebab-case. Hiện dự án dùng partial trong frontend.partials.
- CSS: kebab-case cho block, block__element cho thành phần, block--modifier cho biến thể; trạng thái dùng is-active. Giữ CSS và selector JavaScript đồng bộ khi đổi tên.
- Không đổi các khóa dữ liệu, permission, setting hoặc enum value chỉ để đổi kiểu đặt tên vì chúng có thể đã lưu trong database.
