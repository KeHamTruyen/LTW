# Hệ Thống Quản Trị - Pet Service

## Tính năng Admin

Hệ thống quản trị được xây dựng với Tabler Dashboard Template, cung cấp các tính năng:

### 1. Quản lý Bài viết (Posts Management)
**URL:** `/admin/posts`

#### Chức năng:
- ✅ **Xem danh sách bài viết** với phân trang
- ✅ **Tìm kiếm** bài viết theo tiêu đề, nội dung
- ✅ **Lọc** theo trạng thái: Tất cả, Đã xuất bản, Nháp
- ✅ **Thêm bài viết mới** với trình soạn thảo HTML
- ✅ **Chỉnh sửa** bài viết hiện có
- ✅ **Xóa** bài viết (có xác nhận)
- ✅ **Preview** bài viết trước khi xuất bản
- ✅ Upload ảnh đại diện cho bài viết

#### Thông tin hiển thị:
- ID bài viết
- Ảnh đại diện (thumbnail 60x60px)
- Tiêu đề và slug
- Tác giả
- Trạng thái (Published/Draft)
- Ngày đăng
- Các nút thao tác: Xem, Sửa, Xóa

### 2. Quản lý Bình luận (Comments Management)
**URL:** `/admin/comments`

#### Chức năng:
- ✅ **Xem danh sách bình luận** với phân trang (30 items/page)
- ✅ **Lọc** theo trạng thái:
  - Tất cả
  - Chờ duyệt (Pending)
  - Đã duyệt (Approved)
  - Từ chối (Rejected)
  - Spam
- ✅ **Duyệt** bình luận (Approve)
- ✅ **Từ chối** bình luận (Reject)
- ✅ **Đánh dấu Spam**
- ✅ **Xóa** bình luận vĩnh viễn

#### Thông tin hiển thị:
- Avatar người bình luận
- Tên và email người bình luận
- Đánh giá (rating stars nếu có)
- Nội dung bình luận
- Bài viết được bình luận (có link)
- IP address
- Trạng thái (badge màu)
- Ngày giờ bình luận
- Các nút thao tác theo trạng thái

### 3. Dashboard (Tổng quan)
**URL:** `/admin`

#### Thống kê hiển thị:
- 📊 Tổng số bài viết
- 📝 Số bài viết nháp
- 💬 Số bình luận chờ duyệt
- 👥 Tổng số người dùng
- 📨 Tổng số bình luận

#### Thao tác nhanh:
- Thêm bài viết mới
- Quản lý bài viết
- Quản lý bình luận

## Hệ Thống Authentication

### Đăng nhập
**URL:** `/login`

#### Thông tin đăng nhập demo:
- **Email:** `admin@petcare.com`
- **Password:** `password`

#### Tính năng:
- ✅ Xác thực email và password
- ✅ Ghi nhớ đăng nhập (30 ngày)
- ✅ Session management
- ✅ CSRF protection
- ✅ Flash messages cho lỗi/thành công

### Đăng xuất
**URL:** `/logout`
- Xóa session
- Xóa remember me cookie
- Redirect về trang chủ

## Cấu Trúc Thư Mục

```
app/
├── controllers/
│   ├── Admin/
│   │   ├── PageController.php      # Dashboard
│   │   ├── PostController.php      # Quản lý bài viết
│   │   └── CommentController.php   # Quản lý bình luận
│   └── AuthController.php          # Authentication
├── views/
│   ├── admin/
│   │   ├── dashboard.php           # Trang tổng quan
│   │   ├── posts/
│   │   │   ├── index.php          # Danh sách bài viết
│   │   │   └── form.php           # Form thêm/sửa
│   │   └── comments/
│   │       └── index.php          # Danh sách bình luận
│   ├── auth/
│   │   └── login.php              # Trang đăng nhập
│   └── layouts/
│       └── admin.php              # Layout admin (Tabler)
└── models/
    ├── Post.php                   # Model bài viết
    └── PostComment.php            # Model bình luận
```

## Routes (Định tuyến)

### Public Routes
```php
GET  /                    # Trang chủ
GET  /login              # Trang đăng nhập
POST /login              # Xử lý đăng nhập
GET  /logout             # Đăng xuất
GET  /posts              # Danh sách bài viết (public)
GET  /posts/show         # Chi tiết bài viết
POST /posts/comment      # Gửi bình luận
```

### Admin Routes (Cần đăng nhập với role=admin)
```php
GET  /admin                      # Dashboard
GET  /admin/posts               # Danh sách bài viết
GET  /admin/posts/create        # Form thêm bài viết
POST /admin/posts/store         # Lưu bài viết mới
GET  /admin/posts/edit          # Form sửa bài viết
POST /admin/posts/update        # Cập nhật bài viết
POST /admin/posts/delete        # Xóa bài viết

GET  /admin/comments            # Danh sách bình luận
POST /admin/comments/approve    # Duyệt bình luận
POST /admin/comments/reject     # Từ chối bình luận
POST /admin/comments/spam       # Đánh dấu spam
POST /admin/comments/delete     # Xóa bình luận
```

## Bảo Mật

### Authentication Check
Tất cả routes admin đều được bảo vệ bởi middleware trong constructor:

```php
public function __construct()
{
    // Check admin authentication
    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
        header('Location: ' . BASE_URL . 'login');
        exit;
    }
}
```

### CSRF Protection
Tất cả form POST/DELETE đều có CSRF token:

```php
<input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
```

Và được kiểm tra trong controller:

```php
if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
    $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
    header('Location: ...');
    exit;
}
```

## UI/UX Features (Tabler)

### Layout Components
- ✅ Responsive navbar với dropdown user menu
- ✅ Horizontal navigation menu với active states
- ✅ Flash messages (success/error) với auto-dismiss
- ✅ Pagination với page numbers
- ✅ Status badges với màu sắc phù hợp
- ✅ Card-based layout
- ✅ Empty states cho danh sách trống
- ✅ Avatar placeholders (UI Avatars API)

### Icons
Sử dụng Tabler Icons (stroke-based SVG):
- 📝 Document icons cho bài viết
- 💬 Message icons cho bình luận
- 👁️ Eye icon cho xem
- ✏️ Edit icon cho sửa
- 🗑️ Trash icon cho xóa
- ✅ Check icon cho duyệt
- ❌ X icon cho từ chối

### Tables
- Responsive table với scroll horizontal
- Fixed width columns cho actions
- Hover effects
- Image thumbnails với object-fit
- Badge status indicators

### Forms
- Label required indicators
- Input validation (HTML5)
- Textarea với row settings
- File upload với image preview
- Radio/Checkbox styling
- Form hints/help text

## Database Schema

### Tables sử dụng
```sql
-- Bài viết
posts (id, title, slug, summary, content_html, cover_image_url, 
       author_user_id, status, published_at, created_at, updated_at)

-- Bình luận
comments (id, post_id, user_id, author_name, author_email, 
          content, rating, status, ip_address, created_at)

-- Người dùng
users (id, name, email, password_hash, role, status, created_at)
```

## Hướng Dẫn Cài Đặt

1. **Chạy migration database:**
```bash
php -f database/migrate.php
```

2. **Seed dữ liệu demo:**
```bash
php -f database/seed.php
```

Hoặc truy cập: `http://localhost/LTW/setup.php`

3. **Tạo tài khoản admin (nếu chưa có):**
```sql
INSERT INTO users (name, email, password_hash, role, status, created_at) 
VALUES ('Admin', 'admin@petcare.com', 
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
        'admin', 'active', NOW());
```
Password: `password`

## Tech Stack

### Backend
- **PHP 7.4+** - Server-side language
- **MySQL/MariaDB** - Database
- **PDO** - Database abstraction layer
- **Custom MVC** - Application structure
- **Custom Router** - URL routing

### Frontend
- **Tabler 1.0+** - Admin dashboard template
- **Bootstrap 5** - CSS framework (included in Tabler)
- **Tabler Icons** - Icon set
- **Vite** - Asset bundler

### Security
- **Password Hashing** - bcrypt (cost 10)
- **CSRF Protection** - Token-based
- **XSS Prevention** - htmlspecialchars()
- **SQL Injection** - Prepared statements (PDO)

## Tính Năng Bổ Sung (Có thể phát triển)

### Posts Management
- [ ] Bulk actions (delete multiple posts)
- [ ] Categories/Tags management
- [ ] SEO meta fields
- [ ] Scheduled publishing
- [ ] Draft autosave
- [ ] Rich text editor (TinyMCE/CKEditor)
- [ ] Image gallery
- [ ] Post revisions/history

### Comments Management
- [ ] Bulk actions (approve/delete multiple)
- [ ] Comment replies
- [ ] Email notifications
- [ ] Spam detection (Akismet)
- [ ] Comment moderation settings
- [ ] Export comments

### Dashboard
- [ ] Charts (posts per month, comments trend)
- [ ] Recent activity log
- [ ] Quick stats cards with trends
- [ ] System health status

### Users Management
- [ ] User CRUD operations
- [ ] Role management
- [ ] Permissions system
- [ ] User activity log

### System Settings
- [ ] Site settings (title, description)
- [ ] Email settings (SMTP)
- [ ] Theme customization
- [ ] Backup/Restore

## Troubleshooting

### Không đăng nhập được
1. Kiểm tra database có user admin chưa
2. Verify password hash đúng
3. Check session đang hoạt động
4. Xem error logs

### CSRF token không hợp lệ
1. Kiểm tra session timeout
2. Clear browser cookies
3. Verify session.cookie_lifetime trong php.ini

### Upload ảnh không hoạt động
1. Kiểm tra quyền folder `uploads/`
2. Check `upload_max_filesize` trong php.ini
3. Verify `post_max_size` setting

## Support & Documentation

- **Tabler Documentation:** https://docs.tabler.io/
- **Tabler Demo:** https://tabler.io/admin-template/preview
- **Tabler Icons:** https://tabler-icons.io/

---

**Developed with ❤️ using Tabler Admin Template**
