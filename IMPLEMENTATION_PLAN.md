# Kế Hoạch Triển Khai - Assignment LTW

##  Phân Tích Tình Trạng Hiện Tại

###  Đã Hoàn Thành

1. **Kiến trúc MVC thuần PHP**
   - Router system
   - Controller base class
   - Model base class với Database abstraction
   - View system với layouts
   - Auth system

2. **Authentication & Authorization**
   - Đăng ký/Đăng nhập
   - Session management
   - CSRF protection
   - Role-based access (customer, staff, admin)

3. **Blog/Tin tức (#4)**
   -  Public: Danh sách bài viết, chi tiết bài viết
   -  Tìm kiếm bài viết
   -  Bình luận/đánh giá
   -  Admin: CRUD bài viết
   -  Admin: Quản lý bình luận

4. **Admin Dashboard**
   - Template Tabler đã tích hợp
   - Dashboard với thống kê cơ bản
   - Upload images

### Đang Phát Triển / Cần Hoàn Thiện

1. **Trang chủ (Homepage)**
   - Layout có sẵn nhưng nội dung chưa hoàn chỉnh
   - Cần hero section, features, testimonials, etc.

2. **About Page (#2)**
   - Có trong LTW-part2 nhưng chưa tích hợp vào hệ thống chính
   - Cần migrate vào router chính

3. **FAQ (#2)**
   - Có trong LTW-part2 nhưng chưa tích hợp
   - Admin CRUD đã có

4. **Contact (#1)**
   - Chưa có trang liên hệ
   - Chưa có quản lý liên hệ

### Chưa Có

1. **Products (#3)**
   - Chưa có trang sản phẩm
   - Chưa có database schema cho products
   - Chưa có admin quản lý sản phẩm

2. **Cart & Orders (#3)**
   - Chưa có giỏ hàng
   - Chưa có đơn hàng
   - Chưa có database schema

3. **Pricing**
   - Chưa có bảng giá
   - Có thể là một loại page hoặc table riêng

4. **User Management (Admin)**
   - Chưa có CRUD người dùng
   - Chưa có reset mật khẩu, khóa tài khoản

5. **Page Content Management (#1)**
   - Chưa có hệ thống quản lý nội dung trang
   - Chưa có table `pages` trong database

6. **Contact Management (#1)**
   - Chưa có database table `contacts`
   - Chưa có admin quản lý liên hệ

7. **Categories**
   - Database có thể thiếu bảng categories (cần kiểm tra)

##  Yêu Cầu Theo Đề Bài

### Công Việc Chung (Tất cả thành viên)

- [x] Thiết kế mô hình ứng dụng MVC
- [x] Thiết kế cơ sở dữ liệu quan hệ
- [x] Thiết kế template chung
- [x] Đăng ký/Đăng nhập, phân quyền
- [x] Thay đổi thông tin, mật khẩu, avatar (cần kiểm tra)
- [ ] Quản lý người dùng (admin): xem, reset mật khẩu, khóa user

### Công Việc #1: Trang chủ + Liên hệ + Quản lý

- [ ] **Giao diện:**
  - [ ] Trang chủ hoàn chỉnh
  - [ ] Trang Liên hệ (form gửi liên hệ)

- [ ] **Quản lý (Admin):**
  - [ ] Quản lý nội dung trang (pages management)
  - [ ] Quản lý liên hệ (contacts management)

### Công Việc #2: Giới thiệu + FAQ

- [ ] **Giao diện:**
  - [ ] Trang Giới thiệu (About)
  - [ ] Trang Hỏi/đáp (FAQ) - đã có trong LTW-part2, cần tích hợp

- [ ] **Quản lý (Admin):**
  - [ ] Quản lý nội dung trang Giới thiệu
  - [ ] CRUD FAQ - đã có, cần tích hợp

### Công Việc #3: Sản phẩm + Giỏ hàng

- [ ] **Giao diện:**
  - [ ] Trang danh sách sản phẩm (tìm kiếm)
  - [ ] Trang chi tiết sản phẩm
  - [ ] Giỏ hàng

- [ ] **Quản lý (Admin):**
  - [ ] CRUD sản phẩm
  - [ ] Quản lý đơn hàng

### Công Việc #4: Tin tức

- [x] **Giao diện:**
  - [x] Danh sách bài viết
  - [x] Chi tiết bài viết
  - [x] Tìm kiếm

- [x] **Quản lý (Admin):**
  - [x] CRUD tin tức
  - [x] Quản lý bình luận

## 🗄️ Database Schema Cần Bổ Sung

### Tables Cần Thêm

```sql
-- Categories (cho sản phẩm và có thể cho bài viết)
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE = InnoDB;

-- Products
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) NULL,
    stock_quantity INT UNSIGNED DEFAULT 0,
    sku VARCHAR(100) NULL UNIQUE,
    category_id BIGINT UNSIGNED NULL,
    image_url VARCHAR(255) NULL,
    gallery_images TEXT NULL, -- JSON array
    status ENUM('draft', 'published', 'out_of_stock') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE = InnoDB;

-- Shopping Cart (có thể dùng session, nhưng lưu DB để persistent)
CREATE TABLE cart_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL, -- NULL nếu guest
    session_id VARCHAR(255) NULL, -- Cho guest
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id, session_id)
) ENGINE = InnoDB;

-- Orders
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NULL,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(160) NOT NULL,
    customer_phone VARCHAR(30) NOT NULL,
    shipping_address TEXT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50) NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE = InnoDB;

-- Order Items
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    product_name VARCHAR(255) NOT NULL, -- Snapshot at time of order
    product_price DECIMAL(10,2) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE = InnoDB;

-- Contacts (liên hệ từ khách hàng)
CREATE TABLE contacts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    phone VARCHAR(30) NULL,
    subject VARCHAR(255) NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    replied_at DATETIME NULL,
    replied_by BIGINT UNSIGNED NULL,
    reply_message TEXT NULL,
    ip_address VARCHAR(64) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (replied_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE = InnoDB;

-- Pages (quản lý nội dung trang)
CREATE TABLE pages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    content_html MEDIUMTEXT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    status ENUM('draft', 'published') DEFAULT 'published',
    updated_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE = InnoDB;

-- FAQ (đã có trong LTW-part2, cần migrate)
CREATE TABLE faqs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    display_order INT UNSIGNED DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- About Page (single row, có thể dùng pages table)
-- Hoặc tạo table riêng như trong LTW-part2
CREATE TABLE about_page (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) DEFAULT 'About us',
    description TEXT,
    mission TEXT,
    vision TEXT,
    image VARCHAR(255),
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;
```

### Tables Cần Kiểm Tra/Cập Nhật

1. **posts** - Có thể cần thêm `category_id` nếu chưa có
2. **categories** - Cần thêm cho posts nếu muốn phân loại

## 🎯 Kế Hoạch Triển Khai Chi Tiết

### Phase 1: Database & Models (Ưu tiên)

1. **Cập nhật Database Schema**
   - [ ] Tạo migration script cho tất cả tables mới
   - [ ] Tạo seed data cho demo

2. **Tạo Models**
   - [ ] Category Model
   - [ ] Product Model
   - [ ] Cart Model
   - [ ] Order Model
   - [ ] Contact Model
   - [ ] Page Model
   - [ ] FAQ Model

### Phase 2: Công Việc #1 - Trang chủ + Liên hệ

1. **Trang chủ**
   - [ ] Design layout hoàn chỉnh
   - [ ] Hero section
   - [ ] Features/Highlights
   - [ ] Latest products/blog posts
   - [ ] Testimonials
   - [ ] CTA sections

2. **Trang Liên hệ**
   - [ ] Contact form (tên, email, phone, subject, message)
   - [ ] Validation (JS + PHP)
   - [ ] Google Maps (optional)
   - [ ] Contact info display

3. **Admin: Quản lý nội dung trang**
   - [ ] CRUD pages
   - [ ] WYSIWYG editor
   - [ ] SEO fields

4. **Admin: Quản lý liên hệ**
   - [ ] List contacts với filter (unread/read/replied)
   - [ ] View contact detail
   - [ ] Mark as read/replied
   - [ ] Reply to contact (send email - optional)
   - [ ] Delete contact

### Phase 3: Công Việc #2 - About + FAQ

1. **Trang Giới thiệu**
   - [ ] Migrate từ LTW-part2 hoặc tạo mới
   - [ ] Design layout
   - [ ] Company info, mission, vision

2. **Trang FAQ**
   - [ ] Migrate từ LTW-part2 vào router chính
   - [ ] Display FAQs
   - [ ] Search/filter FAQs

3. **Admin: Quản lý About**
   - [ ] Edit about page content
   - [ ] Upload images

4. **Admin: Quản lý FAQ**
   - [ ] CRUD FAQs (đã có, cần tích hợp)
   - [ ] Reorder FAQs

### Phase 4: Công Việc #3 - Sản phẩm + Giỏ hàng

1. **Trang Sản phẩm**
   - [ ] List products với pagination
   - [ ] Filter by category
   - [ ] Search products
   - [ ] Product detail page
   - [ ] Product gallery

2. **Giỏ hàng**
   - [ ] Add to cart (session + DB)
   - [ ] View cart
   - [ ] Update quantity
   - [ ] Remove from cart
   - [ ] Checkout form

3. **Admin: Quản lý Sản phẩm**
   - [ ] CRUD products
   - [ ] Upload images
   - [ ] Manage stock
   - [ ] Categories management

4. **Admin: Quản lý Đơn hàng**
   - [ ] List orders với filter
   - [ ] View order detail
   - [ ] Update order status
   - [ ] Export orders (optional)

### Phase 5: User Management (Công việc chung)

1. **Admin: Quản lý người dùng**
   - [ ] List users với filter
   - [ ] View user detail
   - [ ] Edit user info
   - [ ] Reset password
   - [ ] Ban/unban user
   - [ ] Delete user

2. **Member: Profile**
   - [ ] View profile
   - [ ] Edit profile
   - [ ] Change password
   - [ ] Upload avatar
   - [ ] View orders (nếu có)

### Phase 6: Pricing Page

1. **Trang Bảng giá**
   - [ ] Design pricing table
   - [ ] Display service/product prices
   - [ ] Có thể dùng Pages management để quản lý

## 📝 Checklist Tính Năng Bắt Buộc

### Validation & Security
- [ ] Client-side validation (JavaScript)
- [ ] Server-side validation (PHP)
- [ ] CSRF protection (đã có)
- [ ] SQL Injection prevention (Prepared statements - đã có)
- [ ] XSS prevention (htmlspecialchars - đã có)
- [ ] File upload validation

### UI/UX
- [ ] Responsive design
- [ ] W3C validation
- [ ] SEO optimization
- [ ] Pagination (đã có cho posts)
- [ ] Image lazy loading
- [ ] Form validation feedback

### Admin Dashboard
- [ ] Tabler template (đã có)
- [ ] Flash messages (đã có)
- [ ] Pagination cho tất cả lists
- [ ] Search/filter functionality
- [ ] Image upload (đã có)
- [ ] WYSIWYG editor cho content

## 🔧 Công Cụ & Thư Viện Được Phép

- ✅ CSS Framework: Bootstrap, Tabler
- ✅ JavaScript Libraries: jQuery, vanilla JS libraries
- ✅ Icons: Tabler Icons
- ✅ WYSIWYG: TinyMCE, CKEditor
- ✅ Image Upload: Drag & drop libraries
- ❌ PHP Frameworks: Laravel, CodeIgniter, etc.
- ❌ CMS: WordPress, Drupal, etc.

##  Thứ Tự Ưu Tiên Thực Hiện

1. **Database Schema** - Tạo tất cả tables cần thiết
2. **Models** - Tạo các Models cho entities mới
3. **Công việc #1** - Trang chủ + Liên hệ (hoàn thành sớm)
4. **Công việc #2** - Tích hợp About + FAQ
5. **Công việc #3** - Sản phẩm + Giỏ hàng (phức tạp nhất)
6. **User Management** - Hoàn thiện admin features
7. **Polish & Testing** - Validation, security, responsive

##  Bắt Đầu Triển Khai

Chọn một trong các cách sau:

1. **Tạo Database Migration Script** - Tạo file SQL migration cho tất cả tables
2. **Bắt đầu với Công việc #1** - Implement trang chủ và liên hệ
3. **Tích hợp LTW-part2** - Migrate About và FAQ vào hệ thống chính
4. **Tạo Products System** - Bắt đầu với sản phẩm và giỏ hàng

---

**Ghi chú:** File này sẽ được cập nhật theo tiến độ thực hiện.

