# 📚 HƯỚNG DẪN SỬ DỤNG HỆ THỐNG QUẢN TRỊ

## 🎯 Tổng Quan

Hệ thống quản trị được xây dựng với **Tabler Dashboard Template** - một framework admin hiện đại, responsive và đầy đủ tính năng.

---

## 🔐 ĐĂNG NHẬP

### Thông tin đăng nhập mặc định:

```
Email: admin@example.com
Password: admin123
```

### Đường dẫn:

```
http://localhost/LTW/public/login
```

### Tính năng:

- ✅ Xác thực với session
- ✅ Bảo vệ CSRF
- ✅ Kiểm tra role admin
- ✅ Remember me (tùy chọn)

---

## 📊 DASHBOARD (Trang tổng quan)

### Đường dẫn:

```
http://localhost/LTW/public/admin
```

### Thống kê hiển thị:

1. **Tổng bài viết** - Số lượng bài đã xuất bản + nháp
2. **Bình luận chờ duyệt** - Số bình luận pending
3. **Tổng bình luận** - Tất cả bình luận
4. **Đánh giá trung bình** - Rating trung bình

### Tính năng nhanh:

- 🆕 Tạo bài viết mới
- 📝 Xem danh sách bài viết
- 💬 Quản lý bình luận

---

## 📝 QUẢN LÝ TIN TỨC (POSTS MANAGEMENT)

### 1️⃣ Danh Sách Bài Viết

**Đường dẫn:**

```
http://localhost/LTW/public/admin/posts
```

**Tính năng:**

#### 🔍 Tìm kiếm & Lọc:

- Tìm kiếm theo tiêu đề/nội dung
- Lọc theo trạng thái:
  - **Tất cả** - Hiển thị tất cả bài viết
  - **Published** - Chỉ bài đã xuất bản
  - **Draft** - Chỉ bài nháp

#### 📋 Bảng danh sách hiển thị:

- Hình ảnh thumbnail (nếu có)
- Tiêu đề bài viết
- Slug (đường dẫn)
- Trạng thái (Published/Draft)
- Tác giả
- Ngày tạo/xuất bản
- Thao tác (Sửa/Xóa)

#### ⚙️ Thao tác:

- ✏️ **Sửa** - Chỉnh sửa bài viết
- 🗑️ **Xóa** - Xóa bài viết (có xác nhận)
- 👁️ **Xem** - Xem trên trang public

#### 📄 Phân trang:

- 20 bài/trang
- Điều hướng trang đơn giản

---

### 2️⃣ Thêm Bài Viết Mới

**Đường dẫn:**

```
http://localhost/LTW/public/admin/posts/create
```

**Form nhập liệu:**

#### 📝 Thông tin cơ bản:

1. **Tiêu đề** (Required)
   - Tự động tạo slug từ tiêu đề
   - Slug có thể chỉnh sửa thủ công
2. **Slug** (Required, unique)

   - URL-friendly
   - Tự động từ tiêu đề hoặc nhập thủ công

3. **Tóm tắt** (Optional)

   - Mô tả ngắn gọn về bài viết
   - Hiển thị trong listing page

4. **Nội dung** (Required)

   - Sử dụng TinyMCE Editor
   - Hỗ trợ:
     - Format text (Bold, Italic, Underline)
     - Heading (H1-H6)
     - Lists (Ordered/Unordered)
     - Links
     - Images
     - Tables
     - Code blocks

5. **Ảnh bìa** (Optional)

   - Upload file ảnh
   - Định dạng: JPG, PNG, GIF, WEBP
   - Kích thước tối đa: 5MB
   - Tự động lưu vào `/uploads/`

6. **Trạng thái** (Required)
   - **Draft** - Lưu nháp (không hiển thị public)
   - **Published** - Xuất bản (hiển thị public)

#### 🛡️ Bảo mật:

- CSRF token protection
- Validate input
- Sanitize HTML content
- Secure file upload

#### 💾 Lưu bài viết:

- Nhấn **"Lưu bài viết"** để submit
- Tự động redirect về danh sách sau khi lưu
- Hiển thị thông báo thành công/lỗi

---

### 3️⃣ Chỉnh Sửa Bài Viết

**Đường dẫn:**

```
http://localhost/LTW/public/admin/posts/edit?id={post_id}
```

**Tính năng:**

- Form giống với tạo mới
- Pre-fill dữ liệu hiện tại
- Xem trước trên public site
- Xóa ảnh bìa hiện tại
- Cập nhật `updated_at` timestamp

**Thao tác:**

- ✏️ Chỉnh sửa mọi trường
- 🖼️ Thay đổi ảnh bìa
- 📊 Thay đổi trạng thái
- 💾 Lưu thay đổi
- ❌ Hủy và quay lại

---

### 4️⃣ Xóa Bài Viết

**Endpoint:**

```
POST http://localhost/LTW/public/admin/posts/delete
```

**Quy trình:**

1. Click nút "Xóa" trên danh sách
2. Hiện popup xác nhận
3. Xác nhận xóa
4. Bài viết và tất cả bình luận liên quan bị xóa
5. Redirect về danh sách

**Lưu ý:**

- ⚠️ Không thể khôi phục sau khi xóa
- 🔗 Xóa cascade comments
- 🛡️ CSRF protection

---

## 💬 QUẢN LÝ BÌNH LUẬN (COMMENTS MANAGEMENT)

### 1️⃣ Danh Sách Bình Luận

**Đường dẫn:**

```
http://localhost/LTW/public/admin/comments
```

**Tính năng:**

#### 🏷️ Lọc theo trạng thái:

- **Tất cả** - Hiển thị tất cả
- **Pending** - Chờ duyệt (cần xử lý)
- **Approved** - Đã duyệt (hiển thị public)
- **Rejected** - Từ chối (không hiển thị)
- **Spam** - Đánh dấu spam

#### 📋 Thông tin hiển thị:

- Avatar người dùng
- Tên người comment
- Email
- Nội dung bình luận
- Rating (1-5 sao)
- Bài viết liên quan
- Thời gian tạo
- IP address
- Trạng thái hiện tại

#### 🎨 Màu sắc trạng thái:

- 🟡 **Pending** - Badge vàng
- 🟢 **Approved** - Badge xanh
- 🔴 **Rejected** - Badge đỏ
- ⚫ **Spam** - Badge đen

---

### 2️⃣ Thao Tác Với Bình Luận

#### ✅ Duyệt bình luận (Approve)

```
POST /admin/comments/approve
Data: comment_id
```

- Thay đổi status → `approved`
- Bình luận sẽ hiển thị trên public site
- Cập nhật `updated_at`

#### ❌ Từ chối bình luận (Reject)

```
POST /admin/comments/reject
Data: comment_id
```

- Thay đổi status → `rejected`
- Ẩn bình luận khỏi public site

#### 🚫 Đánh dấu spam (Spam)

```
POST /admin/comments/spam
Data: comment_id
```

- Thay đổi status → `spam`
- Ghi nhận IP spam
- Có thể auto-reject từ IP này

#### 🗑️ Xóa bình luận (Delete)

```
POST /admin/comments/delete
Data: comment_id
```

- Xóa vĩnh viễn
- Không thể khôi phục
- Có popup xác nhận

#### 🔄 Thao tác hàng loạt:

- Chọn nhiều bình luận (checkbox)
- Áp dụng action cho tất cả
- Thông báo số lượng đã xử lý

---

## 🎨 GIAO DIỆN TABLER DASHBOARD

### Đặc điểm:

- ✨ **Modern & Clean** - Giao diện hiện đại, sạch sẽ
- 📱 **Responsive** - Tương thích mọi thiết bị
- 🎯 **User-friendly** - Dễ sử dụng, trực quan
- 🚀 **Fast** - Tải nhanh, mượt mà
- 🌈 **Colorful** - Màu sắc bắt mắt, phân biệt trạng thái

### Components sử dụng:

- 📊 **Cards** - Thống kê, thông tin
- 📋 **Tables** - Danh sách dữ liệu
- 🔘 **Buttons** - Thao tác CRUD
- 🏷️ **Badges** - Trạng thái
- 📝 **Forms** - Nhập liệu
- 🔔 **Alerts** - Thông báo flash
- 📄 **Pagination** - Phân trang
- 🎭 **Modals** - Xác nhận xóa

### Navigation:

- 📍 **Sidebar** - Menu chính
  - Dashboard
  - Quản lý tin tức
  - Quản lý bình luận
- 👤 **User dropdown** - Thông tin user, logout
- 📱 **Mobile menu** - Hamburger menu

---

## 🔧 TÍNH NĂNG KỸ THUẬT

### 1. Authentication & Authorization

```php
// Middleware check trong constructor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login');
    exit;
}
```

### 2. CSRF Protection

```php
// Tạo token
$_SESSION['csrf'] = bin2hex(random_bytes(32));

// Validate
if ($_POST['csrf'] !== $_SESSION['csrf']) {
    // Reject request
}
```

### 3. File Upload Security

- Validate file type
- Check file size
- Generate unique filename
- Move to safe directory
- Store relative path in DB

### 4. Input Validation

```php
$errors = [];

if (empty(trim($_POST['title']))) {
    $errors[] = 'Tiêu đề không được để trống';
}

if (mb_strlen(trim($_POST['title'])) > 255) {
    $errors[] = 'Tiêu đề không được quá 255 ký tự';
}

// ... more validations
```

### 5. Flash Messages

```php
// Set message
$_SESSION['flash_success'] = 'Lưu thành công!';
$_SESSION['flash_error'] = 'Có lỗi xảy ra!';

// Display (auto-clear)
<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['flash_success'] ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>
```

### 6. Database Queries

- Sử dụng PDO prepared statements
- Prevent SQL injection
- Transaction support (nếu cần)

---

## 📂 CẤU TRÚC FILE

```
app/
├── controllers/
│   └── Admin/
│       ├── PageController.php      # Dashboard
│       ├── PostController.php      # CRUD Posts
│       └── CommentController.php   # Manage Comments
├── models/
│   ├── Post.php                    # Post model
│   └── PostComment.php             # Comment model
├── views/
│   ├── layouts/
│   │   └── admin.php               # Admin layout template
│   └── admin/
│       ├── dashboard.php           # Dashboard view
│       ├── posts/
│       │   ├── index.php           # Posts listing
│       │   └── form.php            # Create/Edit form
│       └── comments/
│           └── index.php           # Comments listing
└── core/
    ├── Controller.php
    ├── Router.php
    └── Auth.php

public/
├── index.php                       # Front controller
└── uploads/                        # Uploaded files

database/
├── schema.sql                      # Database structure
└── seed.php                        # Initial data
```

---

## 🚀 HƯỚNG DẪN CÀI ĐẶT

### 1. Setup Database

```
Truy cập: http://localhost/LTW/database/seed.php
```

Tự động:

- Tạo database
- Tạo tables
- Tạo admin user
- Tạo sample posts

### 2. Đăng nhập Admin

```
URL: http://localhost/LTW/public/login
Email: admin@example.com
Password: admin123
```

### 3. Truy cập Dashboard

```
URL: http://localhost/LTW/public/admin
```

### 4. Bắt đầu sử dụng!

- Tạo bài viết mới
- Quản lý bình luận
- Xem thống kê

---

## 📸 SCREENSHOTS

### Dashboard

```
┌─────────────────────────────────────────┐
│  Tổng quan                               │
├─────────────────────────────────────────┤
│  ┌─────┐  ┌─────┐  ┌─────┐  ┌─────┐   │
│  │ 42  │  │ 15  │  │ 120 │  │ 4.5 │   │
│  │Posts│  │Pend │  │Coms │  │Star │   │
│  └─────┘  └─────┘  └─────┘  └─────┘   │
│                                         │
│  [Tạo bài viết mới] [Xem bài viết]    │
└─────────────────────────────────────────┘
```

### Posts Management

```
┌─────────────────────────────────────────┐
│  Quản lý tin tức          [+ Thêm mới]  │
├─────────────────────────────────────────┤
│  [Tìm kiếm...] [All|Published|Draft]   │
├─────────────────────────────────────────┤
│  ┌───┬──────────┬────────┬──────────┐  │
│  │📷 │Title     │Status  │Actions   │  │
│  ├───┼──────────┼────────┼──────────┤  │
│  │🖼️ │Post 1    │✅Pub   │✏️ 🗑️   │  │
│  │🖼️ │Post 2    │📝Draft │✏️ 🗑️   │  │
│  └───┴──────────┴────────┴──────────┘  │
│  « 1 2 3 ... 10 »                       │
└─────────────────────────────────────────┘
```

### Comments Management

```
┌─────────────────────────────────────────┐
│  Quản lý bình luận                       │
├─────────────────────────────────────────┤
│  [All|Pending|Approved|Rejected|Spam]   │
├─────────────────────────────────────────┤
│  ┌───┬────────┬─────┬──────────────┐   │
│  │☐  │Author  │⭐   │Comment       │   │
│  ├───┼────────┼─────┼──────────────┤   │
│  │☐  │John    │⭐⭐⭐│Great post... │   │
│  │   │        │     │[✅❌🚫🗑️]    │   │
│  └───┴────────┴─────┴──────────────┘   │
└─────────────────────────────────────────┘
```

---

## 🎯 SHORTCUTS

| Page                 | URL                              |
| -------------------- | -------------------------------- |
| **Login**            | `/login`                         |
| **Dashboard**        | `/admin`                         |
| **Posts List**       | `/admin/posts`                   |
| **New Post**         | `/admin/posts/create`            |
| **Edit Post**        | `/admin/posts/edit?id=X`         |
| **Comments**         | `/admin/comments`                |
| **Pending Comments** | `/admin/comments?status=pending` |
| **Logout**           | `/logout`                        |

---

## 💡 TIPS & BEST PRACTICES

### 1. Quản lý bài viết:

- ✍️ Lưu nháp trước khi xuất bản
- 🖼️ Luôn thêm ảnh bìa đẹp
- 📝 Viết tóm tắt hấp dẫn
- 🔗 Tạo slug SEO-friendly
- 📅 Cập nhật content thường xuyên

### 2. Quản lý bình luận:

- ⚡ Duyệt bình luận nhanh chóng
- 🚫 Xử lý spam kịp thời
- 💬 Phản hồi người dùng
- 📊 Theo dõi rating
- 🔍 Review pending daily

### 3. Bảo mật:

- 🔒 Đổi password mặc định
- 🚪 Logout khi không dùng
- 👥 Không share credentials
- 📱 Truy cập từ mạng an toàn

---

## ❓ TROUBLESHOOTING

### Lỗi: "Token bảo mật không hợp lệ"

**Nguyên nhân:** CSRF token expired  
**Giải pháp:** Refresh trang và thử lại

### Lỗi: Upload ảnh thất bại

**Nguyên nhân:** File quá lớn hoặc format không hỗ trợ  
**Giải pháp:**

- Kiểm tra kích thước < 5MB
- Chỉ upload JPG, PNG, GIF, WEBP

### Lỗi: "Không có quyền truy cập"

**Nguyên nhân:** Không phải admin hoặc chưa đăng nhập  
**Giải pháp:** Đăng nhập với tài khoản admin

### Lỗi: Slug đã tồn tại

**Nguyên nhân:** Slug trùng với bài viết khác  
**Giải pháp:** Thay đổi slug thành unique

---

## 📞 HỖ TRỢ

- 📧 Email: admin@petchoice.com
- 🌐 Website: petchoice.com
- 📱 Hotline: 0898 520 760

---

**© 2025 Pet's Choice - All Rights Reserved**
