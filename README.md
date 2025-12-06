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
```

## Cài đặt nhanh

### 1. Database

- Mở phpMyAdmin (http://localhost/phpmyadmin)
- Tạo database mới: `petcare_db`
- Import file `database/schema.sql`

### 2. Cấu hình

- Mở `app/core/config.php` → chỉnh thông tin MySQL nếu cần:
  - Host: `localhost`
  - Database: `petcare_db`
  - User: `root`
  - Password: (để trống với XAMPP mặc định)

### 3. Cấu hình Apache (.htaccess)

Dự án sử dụng URL rewriting, cần bật `mod_rewrite` trong Apache:

- Mở `C:\xampp\apache\conf\httpd.conf`
- Tìm dòng `#LoadModule rewrite_module modules/mod_rewrite.so`
- Bỏ dấu `#` để uncomment
- Restart Apache

### 4. Assets

- Logo 4 con chó: đã có sẵn tại `public/assets/images/logo.png`
- Tabler admin assets: đã copy vào `public/assets/tabler/`

## Chạy dự án

### URL truy cập

- **Trang Blog**: `http://localhost/LTW/posts`
- **Trang chủ**: `http://localhost/LTW/` (đang phát triển)
- **Admin dashboard**: `http://localhost/LTW/admin` (đang phát triển)

### Routing

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
