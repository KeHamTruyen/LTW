# PetCare — PHP MVC Project

Dự án web quản lý thú cưng với kiến trúc MVC thuần PHP (không dùng framework). Backend chạy trên XAMPP/Apache, Frontend sử dụng HTML/CSS/JS thuần và Tabler CSS cho admin dashboard.

## Yêu cầu

- PHP 7.0+
- XAMPP (Apache + MySQL) trên Windows
- Node.js 18+ và npm (cho FE development nếu cần)

## Cấu trúc dự án

```
LTW/
├── app/                  # Source code MVC chính
│   ├── Models/           # Các model dữ liệu
│   ├── controllers/      # Controller xử lý logic
│   ├── core/             # Core ứng dụng (BaseController, routing...)
│   ├── views/            # Giao diện view cho người dùng
│   └── config.php        # Cấu hình chung
├── database/             # Database SQL
│   ├── complete_database.sql  # File SQL hoàn chỉnh (chỉ cần import file này)
│   └── .htaccess
├── public/               # Static assets, entrypoint index.php
│   ├── assets/
│   ├── index.php
│   └── .htaccess
├── README.md             # Hướng dẫn cài đặt và sử dụng
├── BAO_CAO_DU_AN.md      # Báo cáo dự án (Markdown)
├── BAO_CAO_DU_AN.tex     # Báo cáo dự án (LaTeX)
└── package.json          # Quản lý package Node.js (nếu có)
```

## Cài đặt nhanh

### 1. Clone dự án

```bash
cd C:\xampp\htdocs
git clone https://github.com/KeHamTruyen/LTW.git
cd LTW
```

### 2. Cài đặt Database

**Bước 1: Khởi động XAMPP**

- Mở XAMPP Control Panel
- Start **Apache** và **MySQL**

**Bước 2: Import Database**

- Mở trình duyệt, truy cập: `http://localhost/phpmyadmin`
- Click tab **SQL**
- Copy toàn bộ nội dung file `database/complete_database.sql`
- Paste vào ô SQL và click **Go**
- Database `petcare_db` sẽ được tạo tự động với đầy đủ bảng và dữ liệu mẫu

**Dữ liệu mặc định:**

- Admin: `admin@example.com` / `admin123`
- 5 bài viết mẫu với comments
- Categories, FAQs, pages, home page content

### 3. Cấu hình (nếu cần)

File `app/config.php` đã có cấu hình mặc định cho XAMPP:

- Host: `localhost`
- Database: `petcare_db`
- User: `root`
- Password: (để trống)

**Chỉ cần sửa nếu MySQL của bạn có password hoặc cổng khác.**

### 4. Cấu hình Apache (bật mod_rewrite)

Dự án sử dụng URL rewriting, cần bật `mod_rewrite` trong Apache:

- Mở `C:\xampp\apache\conf\httpd.conf`
- Tìm dòng `#LoadModule rewrite_module modules/mod_rewrite.so`
- Bỏ dấu `#` để uncomment
- Restart Apache

### 5. Truy cập website

Mở trình duyệt và truy cập:

- **Trang chủ**: `http://localhost/LTW/`
- **Blog**: `http://localhost/LTW/posts`
- **Admin**: `http://localhost/LTW/admin` (đăng nhập bằng admin@example.com / admin123)

✅ **Hoàn tất! Website đã sẵn sàng.**

## Chạy dự án

Sau khi cài đặt xong, truy cập các URL sau:

- **Trang chủ**: `http://localhost/LTW/`
- **Blog**: `http://localhost/LTW/posts`
- **Chi tiết bài viết**: `http://localhost/LTW/post/[slug]`
- **Admin Dashboard**: `http://localhost/LTW/admin`
- **Đăng nhập Admin**: `http://localhost/LTW/admin/login`

**Tài khoản Admin mặc định:**

- Email: `admin@example.com`
- Password: `admin123`

### Cách hoạt động của Routing

Dự án sử dụng clean URL với `.htaccess`:

- Tất cả request được redirect vào `public/index.php`
- Router phân tích URL và gọi controller tương ứng
- Ví dụ: `/posts` → `PostController::index()`

## Tính năng hiện tại

### Blog (Public)

- ✅ Danh sách bài viết với phân trang
- ✅ Sắp xếp: mới nhất/cũ nhất/phổ biến
- ✅ Sidebar: danh mục, bài viết gần đây
- ✅ Hero section với logo 4 con chó
- ✅ Responsive design
- ✅ Chi tiết bài viết
- ✅ Bình luận và đánh giá

### Authentication

- ✅ Đăng ký tài khoản
- ✅ Đăng nhập/Đăng xuất
- ✅ Session management
- ✅ CSRF protection

### Admin Dashboard

- ✅ Quản lý bài viết (đang phát triển)
- ✅ Quản lý người dùng (đang phát triển)
- ✅ Thống kê (đang phát triển)

## Kiến trúc MVC

### Router (`app/core/Router.php`)

```php
$router->get('/posts', 'PostController@index');
$router->get('/posts/{id}', 'PostController@show');
$router->post('/posts/{id}/comment', 'PostController@addComment');
```

### Controller (`app/controllers/PostController.php`)

```php
class PostController extends Controller {
    public function index() {
        $posts = Post::all();
        return $this->view('posts/index', ['posts' => $posts], 'public');
    }
}
```

### Model (`app/models/Post.php`)

```php
class Post extends Model {
    protected static $table = 'posts';

    public static function all() {
        return self::query("SELECT * FROM posts ORDER BY published_at DESC");
    }
}
```

### View (`app/views/posts/index.php`)

```php
<!-- Sử dụng layout public.php -->
<!-- Hiển thị danh sách bài viết -->
```

## Gỡ lỗi

### Lỗi 500 Internal Server Error

- Kiểm tra `mod_rewrite` đã bật trong Apache
- Kiểm tra file `.htaccess` có cấu hình đúng
- Xem log: `C:\xampp\apache\logs\error.log`

### CSS/JS không load

- Kiểm tra đường dẫn assets trong view
- Kiểm tra constant `BASE_URL` trong `config.php`
- F12 → Network tab để xem file nào bị 404

### Database connection error

- Kiểm tra MySQL đã start trong XAMPP
- Kiểm tra thông tin kết nối trong `app/core/config.php`
- Kiểm tra database `petcare_db` đã được tạo

## Phát triển tiếp

### Coding Standards

- PHP 7.0+ syntax
- PSR-4 autoloading style
- Prepared statements cho database queries
- htmlspecialchars() cho output
- CSRF token cho forms
- Không dùng framework (yêu cầu assignment)
