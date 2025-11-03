# PetCare – PHP thuần + MySQL (XAMPP)

Thiết kế mô hình ứng dụng và skeleton khởi đầu cho hệ thống dịch vụ chăm sóc thú cưng. Không dùng framework PHP, kiến trúc MVC nhẹ nhàng, router tối giản, PDO kết nối MySQL.

## Mục tiêu & phạm vi
- Vai trò: Khách, Khách hàng, Nhân viên, Quản trị.
- Tính năng cơ bản:
  - Quản lý tài khoản (đăng ký/đăng nhập, cập nhật hồ sơ, đổi mật khẩu).
  - Quản lý thú cưng (thêm/sửa/xóa pet).
  - Danh mục & dịch vụ (xem danh sách dịch vụ, giá, thời lượng).
  - Đặt lịch dịch vụ (chọn pet, chọn dịch vụ, chọn thời gian, tính tổng tiền).
  - Phân công nhân viên xử lý lịch hẹn.
  - Thanh toán (cash/card/transfer – nội bộ demo).
  - Đánh giá dịch vụ sau khi hoàn thành.
  - Quản trị người dùng (xem danh sách, đổi vai trò, khóa/mở khóa, reset mật khẩu).

## Kiến trúc PHP thuần (MVC nhẹ)
```
LTW/
├─ public/               # DocumentRoot
│  ├─ .htaccess          # Rewrite về index.php
│  ├─ index.php          # Front controller + Router
│  └─ assets/{css,js,img}
├─ app/
│  ├─ config.php         # Config chung (DB, BASE_URL, session)
│  ├─ core/              # Lõi khung MVC nhẹ
│  │  ├─ Database.php    # PDO singleton
│  │  ├─ Controller.php  # Base Controller (render view)
│  │  └─ Router.php      # Router tối giản (GET/POST)
│  ├─ controllers/       # Controller người dùng
│  │  └─ HomeController.php
│  ├─ models/            # Model (tùy chọn, dùng Database::conn())
│  └─ views/             # View và layout
│     ├─ layouts/main.php
│     └─ home/index.php
├─ database/
│  └─ schema.sql         # DDL MySQL + seed ví dụ
└─ docs/
   └─ ERD.md             # Mô hình thực thể – quan hệ
```

### Router mẫu
- GET / → HomeController@index
- Auth: GET/POST /login, GET/POST /register, GET /logout
- Profile: GET /profile, POST /profile, POST /profile/password
- Admin: GET /admin/users, POST /admin/users/status|role|reset
- Bạn có thể thêm: GET /services, GET /bookings, POST /bookings, ...

## Cơ sở dữ liệu
Xem `docs/ERD.md` và chạy `database/schema.sql` trong phpMyAdmin.

Các bảng chính: `users, pets, service_categories, services, bookings, booking_services, booking_assignments, payments, reviews, addresses, working_hours`.

## Hướng dẫn chạy với XAMPP (Windows)
1. Mở XAMPP, Start Apache + MySQL.
2. Cách A (nhanh): Copy thư mục `LTW/` vào `C:/xampp/htdocs/` → truy cập `http://localhost/LTW/public/`.
3. Cách B (đẹp URL): cấu hình VirtualHost (Apache)
   - Mở `C:/xampp/apache/conf/extra/httpd-vhosts.conf`, thêm:
     ```
     <VirtualHost *:80>
       ServerName petcare.local
       DocumentRoot "D:/Downloads/LTW/public"
       <Directory "D:/Downloads/LTW/public">
         AllowOverride All
         Require all granted
       </Directory>
     </VirtualHost>
     ```
   - Thêm vào `C:/Windows/System32/drivers/etc/hosts` dòng: `127.0.0.1  petcare.local`
   - Restart Apache → truy cập `http://petcare.local/`
4. Tạo DB:
   - Mở phpMyAdmin → chạy file `database/schema.sql`.
  - (Nếu đã tạo từ trước) chạy các file trong `database/migrations/*.sql` để bổ sung cột (vd: avatar_url, last_login). Nếu MySQL báo lỗi vì cột đã tồn tại, có thể bỏ qua.
5. Cập nhật `app/config.php` nếu user/password MySQL khác (mặc định XAMPP: root/blank).

## Hợp đồng (contract) nhỏ cho flow Đặt lịch
- Input: user đã login, chọn pet, chọn 1..n dịch vụ, chọn thời gian bắt đầu.
- Xử lý: kiểm tra trùng lịch/khung giờ làm của staff (mở rộng), tính tổng tiền.
- Output: 1 record `bookings` + n record `booking_services`, trạng thái `pending`.
- Lỗi: thiếu pet/dịch vụ, thời gian không hợp lệ, DB lỗi.

## Bảo mật & chất lượng
- Lưu mật khẩu bằng `password_hash()` và kiểm bằng `password_verify()`.
- Chống CSRF cho form POST (token session ẩn).
- Validate/sanitize input (filter_var, regex).
- Chuẩn bị statement PDO (prepared statements) – đã bật ERRMODE_EXCEPTION.
- CORS: không cần cho app server-side thuần; cân nhắc nếu tách SPA.

## Bước tiếp theo gợi ý
- Thêm `AuthController` (login/register/logout) + `User` model.
- Tạo `ServiceController` (list) và `BookingController` (create/store/list).
- Form mẫu và CSRF middleware siêu nhẹ trong Router hoặc Base Controller.
- Upload ảnh thú cưng (kiểm MIME/size, lưu path).
- Email/SMS thông báo xác nhận lịch (tích hợp sau).

## Thử nhanh các chức năng đã có
- Đăng ký → đăng nhập → chỉnh sửa hồ sơ/đổi mật khẩu → upload avatar.
- Tạo 1 tài khoản admin thủ công bằng phpMyAdmin: cập nhật `users.role = 'admin'` cho user của bạn → vào `/admin/users` để quản trị.

---
Skeleton đã sẵn sàng. Bạn chỉ cần nhân rộng pattern Controller → View → Model để triển khai các tính năng thực tế.
