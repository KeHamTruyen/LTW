# LTW - Hệ Thống Quản Lý & Đọc Truyện

Hệ thống này là một nền tảng quản lý và đọc truyện với backend PHP (hỗ trợ MVC) và frontend sử dụng JavaScript, CSS, HTML cùng một số công nghệ khác. Dự án này bao gồm nhiều phần và các module, được tổ chức phục vụ cho nhu cầu quản trị, người dùng, cũng như phát triển thêm các tính năng mở rộng.

## Cấu trúc thư mục

```
LTW/
├── app/                  # Source code MVC chính
│   ├── Models/           # Các model dữ liệu
│   ├── controllers/      # Controller xử lý logic
│   ├── core/             # Core ứng dụng (BaseController, routing...)
│   ├── views/            # Giao diện view cho người dùng
│   └── config.php        # Cấu hình chung
├── database/             # Migrate, seed, schema SQL và script khởi tạo database
│   ├── migrations/
│   ├── migrate_all.php
│   ├── schema.sql
│   ├── seed.php
│   └── seed_posts.sql
├── public/               # Static assets, entrypoint index.php, assets, init-db
│   ├── assets/
│   ├── index.php
│   ├── init-db.php
│   └── .htaccess
├── setup.php             # Script thiết lập mới
├── package.json          # Quản lý package (nếu có dùng npm/yarn)
├── README.md             # File này
├── [ADMIN_GUIDE.md]      # Hướng dẫn cho admin
├── [ASSETS_GUIDE.md]     # Hướng dẫn asset
├── assignment.pdf        # Đề tài bài tập/project
├── LTW-part2/            # **Legacy/Task cũ (nên cân nhắc xóa nếu không dùng)**
├── LTW_task3/            # **Legacy/Task cũ (nên cân nhắc xóa nếu không dùng)**
```

**Gợi ý:** Các thư mục `LTW-part2`, `LTW_task3` chứa code và file từ bài tập cũ, nên cân nhắc xóa khỏi project nếu không còn sử dụng.

## Cài đặt & Chạy hệ thống

1. **Yêu cầu**
   - PHP >= 7.4
   - MySQL/MariaDB
   - Node.js (nếu sử dụng npm/yarn để quản lý JS/CSS asset)
   - Webserver: Apache/Nginx

2. **Các bước cài đặt**
   - Clone repository:
     ```bash
     git clone https://github.com/KeHamTruyen/LTW.git
     cd LTW
     ```
   - Cài đặt dependencies (nếu có `package.json`):
     ```bash
     npm install
     ```
   - Khởi tạo Database:
     - Import `database/schema.sql`
     - Chạy các script trong thư mục `database/` nếu muốn seed dữ liệu test:
       ```bash
       php database/seed.php
       php database/migrate_all.php
       ```
     - Có thể dùng luôn `public/init-db.php` để khởi tạo nếu hỗ trợ web.
   - Cấu hình:
     - Sửa file `app/config.php` (database, môi trường...)
   - Chạy webserver trỏ vào thư mục `public/`  
      *Với Apache đảm bảo `.htaccess` hoạt động.*
   - Truy cập: `http://localhost/` hoặc theo domain/port bạn cấu hình.

## Chức năng chính

- Đăng ký, đăng nhập, quản lý thông tin tài khoản.
- Quản trị truyện (thêm/xóa/sửa truyện, chapter, thể loại...).
- Đọc truyện online giao diện thân thiện.
- Tìm kiếm truyện theo tên, thể loại, tác giả.
- Quản trị và phân quyền user, admin.
- Hỗ trợ migrate và seed dữ liệu mẫu cho developer.

## Công nghệ sử dụng

- **Backend**: PHP (MVC custom/simple), sử dụng MySQL cho lưu trữ.
- **Frontend**: JavaScript, HTML, CSS, có thể có TypeScript & SCSS (tỉ lệ nhỏ).
- **Khác**: Cấu hình webserver `.htaccess` chuẩn, dùng package.json cho asset (nếu có).

## Ghi chú

- Dự án có nhiều thư mục phụ từ các phiên bản hoặc bài tập khác (`LTW-part2`, `LTW_task3`), cần làm sạch trước khi triển khai.
- Xem thêm các file hướng dẫn như `ADMIN_GUIDE.md`, `ASSETS_GUIDE.md` trong root repo.

---

**Link repository:** [KeHamTruyen/LTW](https://github.com/KeHamTruyen/LTW)
