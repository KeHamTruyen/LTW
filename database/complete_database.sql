-- =====================================================
-- COMPLETE DATABASE SETUP FOR PETCARE_DB
-- =====================================================
-- This file combines:
-- 1. Schema creation (tables structure)
-- 2. Migrations (additional tables and columns)
-- 3. Seed data (sample data for testing)
--
-- Usage: Import this file into MySQL/phpMyAdmin
-- =====================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS `petcare_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `petcare_db`;

SET NAMES utf8mb4;

SET time_zone = '+00:00';

-- =====================================================
-- PART 1: BASE SCHEMA (Core Tables)
-- =====================================================

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    phone VARCHAR(30) NULL,
    avatar_url VARCHAR(255) NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'staff', 'admin') NOT NULL DEFAULT 'customer',
    status ENUM(
        'active',
        'inactive',
        'banned'
    ) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    last_login DATETIME NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Posts (News/Articles) Table
CREATE TABLE IF NOT EXISTS posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_user_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    summary TEXT NULL,
    content_html MEDIUMTEXT NULL,
    cover_image_url VARCHAR(255) NULL,
    category_id BIGINT UNSIGNED NULL,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    published_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_posts_status_published_at (status, published_at),
    INDEX idx_category (category_id),
    CONSTRAINT fk_posts_author FOREIGN KEY (author_user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Post Comments Table
CREATE TABLE IF NOT EXISTS post_comments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    author_name VARCHAR(120) NULL,
    author_email VARCHAR(160) NULL,
    rating TINYINT UNSIGNED NULL,
    content TEXT NOT NULL,
    status ENUM(
        'pending',
        'approved',
        'rejected'
    ) NOT NULL DEFAULT 'pending',
    ip_address VARCHAR(64) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_post_comments_status (status),
    CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE,
    CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- PART 2: ADDITIONAL TABLES (From Migrations)
-- =====================================================

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    FOREIGN KEY (parent_id) REFERENCES categories (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Add foreign key for posts.category_id (deferred until categories table exists)
ALTER TABLE posts
ADD CONSTRAINT fk_posts_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL;

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2) NULL,
    stock_quantity INT UNSIGNED DEFAULT 0,
    sku VARCHAR(100) NULL UNIQUE,
    category_id BIGINT UNSIGNED NULL,
    image_url VARCHAR(255) NULL,
    gallery_images TEXT NULL COMMENT 'JSON array of image URLs',
    status ENUM(
        'draft',
        'published',
        'out_of_stock'
    ) DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    INDEX idx_featured (featured),
    FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Shopping Cart Items Table
CREATE TABLE IF NOT EXISTS cart_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL COMMENT 'NULL for guest users',
    session_id VARCHAR(255) NULL COMMENT 'Session ID for guest users',
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_session (session_id),
    INDEX idx_product (product_id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NULL,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(160) NOT NULL,
    customer_phone VARCHAR(30) NOT NULL,
    shipping_address TEXT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM(
        'pending',
        'processing',
        'shipped',
        'delivered',
        'cancelled'
    ) DEFAULT 'pending',
    payment_method VARCHAR(50) NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_number (order_number),
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_created (created_at),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    product_name VARCHAR(255) NOT NULL COMMENT 'Snapshot at time of order',
    product_price DECIMAL(10, 2) NOT NULL COMMENT 'Price at time of order',
    quantity INT UNSIGNED NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    INDEX idx_order (order_id),
    FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Contacts Table (Customer contact submissions)
CREATE TABLE IF NOT EXISTS contacts (
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
    INDEX idx_status (status),
    INDEX idx_created (created_at),
    FOREIGN KEY (replied_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Pages Table (For managing page content)
CREATE TABLE IF NOT EXISTS pages (
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
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    FOREIGN KEY (updated_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- FAQ Table
CREATE TABLE IF NOT EXISTS faqs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    display_order INT UNSIGNED DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_order (display_order)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- About Page Table (Single row table)
CREATE TABLE IF NOT EXISTS about_page (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) DEFAULT 'About us',
    description TEXT,
    mission TEXT,
    vision TEXT,
    image VARCHAR(255),
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Home Page Table
CREATE TABLE IF NOT EXISTS home_page (
    id INT PRIMARY KEY AUTO_INCREMENT,
    -- Hero Section
    hero_title VARCHAR(255) DEFAULT 'A pet store with everything you need',
    hero_subtitle VARCHAR(255) DEFAULT 'Pet\'s Choice',
    hero_button_text VARCHAR(100) DEFAULT 'Shop Now',
    hero_image_url VARCHAR(255) NULL,
    -- Company Info
    company_name VARCHAR(255) DEFAULT 'Pet\'s Choice Store',
    company_phone VARCHAR(50) DEFAULT '+039 871-5611',
    company_email VARCHAR(255) DEFAULT 'petschoice@outlook.com',
    company_address TEXT NULL,
    company_logo_url VARCHAR(255) NULL,
    -- Service Section
    service_title VARCHAR(255) DEFAULT 'Pet Service',
    service_subtitle VARCHAR(255) DEFAULT 'Price Combo',
    -- Service Combos (stored as JSON)
    service_combos TEXT NULL COMMENT 'JSON array of service combos',
    -- About Section
    about_title VARCHAR(255) DEFAULT 'The smarter way to service for your pet',
    about_subtitle VARCHAR(255) DEFAULT 'Pet\'s Choice',
    about_description TEXT NULL,
    about_button_text VARCHAR(100) DEFAULT 'Xem thêm',
    about_image_url VARCHAR(255) NULL,
    -- Products Section
    products_title VARCHAR(255) DEFAULT 'Sản Phẩm',
    products_subtitle VARCHAR(255) DEFAULT 'Nổi Bật',
    products_button_text VARCHAR(100) DEFAULT 'Xem Shop',
    -- Posts Section
    posts_title VARCHAR(255) DEFAULT 'Bài Viết',
    posts_subtitle VARCHAR(255) DEFAULT 'Mới Nhất',
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- PART 3: SEED DATA (Initial/Sample Data)
-- =====================================================

-- Insert default admin user
INSERT INTO
    users (
        name,
        email,
        password_hash,
        role,
        status,
        created_at
    )
VALUES (
        'Administrator',
        'admin@example.com',
        '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O',
        'admin',
        'active',
        NOW()
    )
ON DUPLICATE KEY UPDATE
    name = name;
-- Password: admin123

-- Insert default categories
INSERT INTO
    categories (name, slug, description)
VALUES (
        'Thức ăn',
        'thuc-an',
        'Thức ăn cho thú cưng'
    ),
    (
        'Đồ chơi',
        'do-choi',
        'Đồ chơi cho thú cưng'
    ),
    (
        'Phụ kiện',
        'phu-kien',
        'Phụ kiện cho thú cưng'
    ),
    (
        'Tin tức',
        'tin-tuc',
        'Tin tức về thú cưng'
    ),
    (
        'Hướng dẫn',
        'huong-dan',
        'Hướng dẫn chăm sóc thú cưng'
    )
ON DUPLICATE KEY UPDATE
    name = name;

-- Insert sample posts
INSERT INTO
    posts (
        author_user_id,
        title,
        slug,
        summary,
        content_html,
        status,
        published_at,
        created_at
    )
VALUES (
        1,
        'Chăm sóc thú cưng mùa hè - Những điều cần lưu ý',
        'cham-soc-thu-cung-mua-he',
        'Mùa hè là thời điểm khó khăn cho thú cưng của bạn. Hãy tìm hiểu cách chăm sóc chúng đúng cách trong thời tiết nóng bức.',
        '<h2>Giữ cho thú cưng luôn mát mẻ</h2>
<p>Trong mùa hè, nhiệt độ cao có thể gây nguy hiểm cho thú cưng. Đảm bảo chúng luôn có nước uống mát và nơi trú ẩn thoáng mát.</p>
<h3>Lời khuyên quan trọng:</h3>
<ul>
<li>Không để thú cưng trong xe kín vào mùa hè</li>
<li>Tránh cho chúng ra ngoài vào giữa trưa</li>
<li>Cung cấp nhiều nước uống</li>
<li>Sử dụng thảm làm mát nếu cần</li>
</ul>',
        'published',
        NOW(),
        NOW()
    ),
    (
        1,
        'Dinh dưỡng cân bằng cho chó mèo',
        'dinh-duong-can-bang-cho-cho-meo',
        'Tìm hiểu về chế độ dinh dưỡng phù hợp để thú cưng của bạn luôn khỏe mạnh và năng động.',
        '<h2>Nguyên tắc dinh dưỡng cơ bản</h2>
<p>Chế độ ăn cân bằng là chìa khóa cho sức khỏe của thú cưng. Protein, chất béo, carbohydrate và vitamin đều đóng vai trò quan trọng.</p>
<h3>Thức ăn nên chọn:</h3>
<ul>
<li>Thức ăn khô chất lượng cao</li>
<li>Thức ăn ướt bổ sung</li>
<li>Thịt tươi (nấu chín)</li>
<li>Rau củ an toàn</li>
</ul>
<h3>Tránh các thực phẩm:</h3>
<ul>
<li>Chocolate</li>
<li>Hành tây, tỏi</li>
<li>Nho, nho khô</li>
<li>Xương gà nhỏ</li>
</ul>',
        'published',
        NOW() - INTERVAL 2 DAY,
        NOW() - INTERVAL 2 DAY
    ),
    (
        1,
        'Cách huấn luyện chó cơ bản cho người mới',
        'cach-huan-luyen-cho-co-ban',
        'Hướng dẫn chi tiết các bước huấn luyện chó cơ bản dành cho những người mới bắt đầu nuôi thú cưng.',
        '<h2>Bắt đầu từ những điều đơn giản</h2>
<p>Huấn luyện chó đòi hỏi sự kiên nhẫn và nhất quán. Bắt đầu với các lệnh cơ bản như "ngồi", "nằm", "đứng".</p>
<h3>Nguyên tắc huấn luyện:</h3>
<ol>
<li>Luôn thưởng khi chó làm đúng</li>
<li>Không trừng phạt thể xác</li>
<li>Luyện tập ngắn nhưng thường xuyên</li>
<li>Sử dụng giọng nói rõ ràng</li>
</ol>',
        'published',
        NOW() - INTERVAL 5 DAY,
        NOW() - INTERVAL 5 DAY
    ),
    (
        1,
        'Top 10 giống mèo được yêu thích nhất',
        'top-10-giong-meo-duoc-yeu-thich',
        'Khám phá những giống mèo phổ biến và được nhiều người yêu thích nhất trên thế giới.',
        '<h2>Các giống mèo nổi tiếng</h2>
<p>Từ mèo Ba Tư sang trọng đến mèo Anh lông ngắn đáng yêu, mỗi giống mèo đều có đặc điểm riêng.</p>
<p>Nội dung chi tiết sẽ được cập nhật...</p>',
        'draft',
        NULL,
        NOW()
    ),
    (
        1,
        'Phòng bệnh cho thú cưng - Tiêm phòng đúng cách',
        'phong-benh-cho-thu-cung',
        'Lịch tiêm phòng và các loại vaccine quan trọng để bảo vệ sức khỏe thú cưng của bạn.',
        '<h2>Tầm quan trọng của việc tiêm phòng</h2>
<p>Tiêm phòng giúp thú cưng tránh được nhiều bệnh nguy hiểm. Đây là biện pháp phòng ngừa hiệu quả nhất.</p>
<h3>Các loại vaccine cần thiết:</h3>
<ul>
<li>Vaccine 5 bệnh (cho chó)</li>
<li>Vaccine 4 bệnh (cho mèo)</li>
<li>Vaccine dại (bắt buộc)</li>
</ul>',
        'published',
        NOW() - INTERVAL 1 DAY,
        NOW() - INTERVAL 1 DAY
    )
ON DUPLICATE KEY UPDATE
    title = title;

-- Insert sample comments
INSERT INTO
    post_comments (
        post_id,
        author_name,
        author_email,
        content,
        rating,
        status,
        ip_address,
        created_at
    )
VALUES (
        1,
        'Nguyễn Văn A',
        'vana@example.com',
        'Bài viết rất hữu ích! Cảm ơn tác giả đã chia sẻ.',
        5,
        'approved',
        '127.0.0.1',
        NOW() - INTERVAL 1 HOUR
    ),
    (
        1,
        'Trần Thị B',
        'thib@example.com',
        'Mình đã áp dụng và thấy hiệu quả rõ rệt. Chó nhà mình đỡ nóng hơn nhiều.',
        5,
        'approved',
        '127.0.0.1',
        NOW() - INTERVAL 2 HOUR
    ),
    (
        1,
        'Lê Văn C',
        'vanc@example.com',
        'Có thể cho mình hỏi thêm về loại thảm làm mát nào tốt không?',
        4,
        'pending',
        '127.0.0.1',
        NOW() - INTERVAL 30 MINUTE
    ),
    (
        2,
        'Phạm Thị D',
        'thid@example.com',
        'Bài viết rất chi tiết và dễ hiểu. Cảm ơn nhiều!',
        5,
        'approved',
        '127.0.0.1',
        NOW() - INTERVAL 1 DAY
    ),
    (
        2,
        'Hoàng Văn E',
        'vane@example.com',
        'Mình không biết là chocolate lại độc với chó. Cảm ơn đã cảnh báo!',
        5,
        'approved',
        '127.0.0.1',
        NOW() - INTERVAL 1 DAY
    ),
    (
        3,
        'Đỗ Thị F',
        'thif@example.com',
        'Hướng dẫn rất dễ làm theo. Chó mình đã học được lệnh ngồi rồi!',
        5,
        'approved',
        '127.0.0.1',
        NOW() - INTERVAL 3 HOUR
    ),
    (
        5,
        'Vũ Thị H',
        'thih@example.com',
        'Khi nào cần đi tiêm phòng lần đầu cho chó con?',
        4,
        'pending',
        '127.0.0.1',
        NOW() - INTERVAL 2 HOUR
    )
ON DUPLICATE KEY UPDATE
    author_name = author_name;

-- Insert default FAQ
INSERT INTO
    faqs (
        question,
        answer,
        display_order,
        status
    )
VALUES (
        'Làm thế nào để liên hệ với chúng tôi?',
        'Bạn có thể liên hệ với chúng tôi qua form liên hệ trên website hoặc email support@petcare.com',
        1,
        'active'
    ),
    (
        'Bạn có giao hàng tận nơi không?',
        'Có, chúng tôi giao hàng trên toàn quốc. Phí vận chuyển sẽ được tính dựa trên địa chỉ giao hàng.',
        2,
        'active'
    ),
    (
        'Thời gian giao hàng là bao lâu?',
        'Thời gian giao hàng từ 2-5 ngày làm việc tùy thuộc vào khu vực.',
        3,
        'active'
    )
ON DUPLICATE KEY UPDATE
    question = question;

-- Insert default about page
INSERT INTO
    about_page (
        title,
        description,
        mission,
        vision
    )
SELECT 'Về chúng tôi', 'Chúng tôi là công ty chuyên cung cấp các dịch vụ và sản phẩm chăm sóc thú cưng chất lượng cao.', 'Sứ mệnh của chúng tôi là mang lại sức khỏe và hạnh phúc cho thú cưng của bạn.', 'Trở thành công ty hàng đầu trong lĩnh vực chăm sóc thú cưng tại Việt Nam.'
FROM DUAL
WHERE
    NOT EXISTS (
        SELECT *
        FROM about_page
    );

-- Insert default pages
INSERT INTO
    pages (
        slug,
        title,
        content_html,
        status
    )
VALUES (
        'pricing',
        'Bảng giá',
        '<h2>Bảng giá dịch vụ</h2><p>Nội dung bảng giá sẽ được cập nhật...</p>',
        'published'
    ),
    (
        'service',
        'Dịch vụ',
        '<h2>Dịch vụ của chúng tôi</h2><p>Nội dung dịch vụ sẽ được cập nhật...</p>',
        'published'
    )
ON DUPLICATE KEY UPDATE
    title = title;

-- Insert default home page content
INSERT INTO
    home_page (
        hero_title,
        hero_subtitle,
        hero_button_text,
        hero_image_url,
        company_name,
        company_phone,
        company_email,
        service_title,
        service_subtitle,
        service_combos,
        about_title,
        about_subtitle,
        about_description,
        about_button_text,
        about_image_url,
        products_title,
        products_subtitle,
        products_button_text,
        posts_title,
        posts_subtitle
    )
SELECT
    'A pet store with<br>everything you need',
    'Pet\'s Choice',
    'Shop Now',
    'https://i.imgur.com/7SsNMLp.png',
    'Pet\'s Choice Store',
    '+039 871-5611',
    'petschoice@outlook.com',
    'Pet Service',
    'Price Combo',
    '[{"name":"Combo 1","price":"200.000 VNĐ","items":[{"name":"Tắm sấy","included":true},{"name":"Vệ sinh","included":true},{"name":"Cắt tỉa lông","included":false}]},{"name":"Combo 2","price":"350.000 VNĐ","items":[{"name":"Tắm sấy","included":false},{"name":"Vệ sinh","included":true},{"name":"Cắt tỉa lông","included":true}]},{"name":"Combo 3","price":"400.000 VNĐ","items":[{"name":"Tắm sấy","included":true},{"name":"Vệ sinh","included":true},{"name":"Cắt tỉa lông","included":true}]}]',
    'The smarter way to service for your pet',
    'Pet\'s Choice',
    'Chăm sóc thú cưng tận tâm với đội ngũ chuyên nghiệp, quy trình khoa học và an toàn cho bé.',
    'Xem thêm',
    'https://i.imgur.com/UPYw7ud.png',
    'Sản Phẩm',
    'Nổi Bật',
    'Xem Shop',
    'Bài Viết',
    'Mới Nhất'
FROM DUAL
WHERE
    NOT EXISTS (
        SELECT *
        FROM home_page
    );

-- =====================================================
-- PART 4: EXTENDED SAMPLE DATA
-- =====================================================
-- Dữ liệu mẫu mở rộng: thêm users, products, orders, contacts

-- Thêm users mẫu (staff và customers)
INSERT INTO users (name, email, phone, avatar_url, password_hash, role, status, created_at)
VALUES 
('Nguyễn Văn Staff', 'staff@petcare.com', '0902345678', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'staff', 'active', NOW() - INTERVAL 25 DAY),
('Trần Thị Lan', 'lan.tran@gmail.com', '0903456789', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'customer', 'active', NOW() - INTERVAL 20 DAY),
('Lê Văn Hùng', 'hung.le@gmail.com', '0904567890', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'customer', 'active', NOW() - INTERVAL 15 DAY),
('Phạm Minh Tuấn', 'tuan.pham@gmail.com', '0905678901', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'customer', 'active', NOW() - INTERVAL 10 DAY),
('Hoàng Thu Hà', 'ha.hoang@gmail.com', '0906789012', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'customer', 'active', NOW() - INTERVAL 5 DAY)
ON DUPLICATE KEY UPDATE name=name;

-- Cập nhật categories với subcategories
INSERT INTO categories (name, slug, description, parent_id) VALUES
('Thức ăn cho chó', 'thuc-an-cho-cho', 'Thức ăn dinh dưỡng cho chó các loại', NULL),
('Thức ăn cho mèo', 'thuc-an-cho-meo', 'Thức ăn dinh dưỡng cho mèo các loại', NULL),
('Vệ sinh & Chăm sóc', 've-sinh-cham-soc', 'Sản phẩm vệ sinh và chăm sóc thú cưng', NULL),
('Sức khỏe & Y tế', 'suc-khoe-y-te', 'Vitamin, thuốc và sản phẩm y tế cho thú cưng', NULL),
('Thức ăn hạt', 'thuc-an-hat-cho', 'Thức ăn khô dạng hạt cho chó', 1),
('Thức ăn ướt', 'thuc-an-uot-cho', 'Thức ăn đóng hộp, pate cho chó', 1),
('Snack & Bánh thưởng', 'snack-banh-thuong-cho', 'Bánh thưởng, snack cho chó', 1),
('Thức ăn hạt cho mèo', 'thuc-an-hat-meo', 'Thức ăn khô dạng hạt cho mèo', 2),
('Pate & Thức ăn ướt', 'pate-thuc-an-uot-meo', 'Pate, thức ăn ướt cho mèo', 2),
('Snack cho mèo', 'snack-cho-meo', 'Bánh thưởng, snack cho mèo', 2)
ON DUPLICATE KEY UPDATE description=VALUES(description);

-- Thêm sản phẩm mẫu
INSERT INTO products (name, slug, description, price, sale_price, stock_quantity, sku, category_id, image_url, status, featured, meta_title, meta_description)
VALUES
('Royal Canin Medium Adult 10kg', 'royal-canin-medium-adult-10kg', 
'Thức ăn hoàn chỉnh dành cho chó trưởng thành giống vừa (11kg - 25kg). Công thức độc quyền giúp tăng cường sức đề kháng, duy trì cân nặng lý tưởng và hỗ trợ sức khỏe da lông.', 
1250000, 1150000, 50, 'RC-MA-10KG', 7, 'https://cdn.royalcanin-weshare-online.io/rOyBxGABaxEApS7LuQnT/v1/bd29h-dog-product-hero-medium-adult', 'published', TRUE,
'Royal Canin Medium Adult 10kg - Thức ăn chó trưởng thành', 
'Thức ăn Royal Canin cho chó giống vừa, giúp tăng cường sức đề kháng và duy trì cân nặng lý tưởng'),
('Pedigree Adult Grilled Liver 3kg', 'pedigree-adult-grilled-liver-3kg',
'Thức ăn khô Pedigree vị gan nướng dành cho chó trưởng thành. Chứa đầy đủ dinh dưỡng với protein, vitamin và khoáng chất thiết yếu.',
450000, 399000, 100, 'PDG-AL-3KG', 7, 'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=500', 'published', TRUE,
'Pedigree Adult 3kg - Thức ăn chó giá rẻ chất lượng',
'Thức ăn Pedigree cho chó trưởng thành, dinh dưỡng hoàn chỉnh với giá cả phải chăng'),
('Royal Canin Persian Adult 2kg', 'royal-canin-persian-adult-2kg',
'Thức ăn chuyên biệt cho mèo Ba Tư trưởng thành. Hình dạng hạt đặc biệt giúp mèo lông dài dễ ăn, giảm búi lông.',
580000, 550000, 60, 'RC-PA-2KG', 10, 'https://cdn.royalcanin-weshare-online.io/roCBGGABaxEApS7Lumnf/v5/fbn-persian-cv-eretailkit', 'published', TRUE,
'Royal Canin Persian 2kg - Thức ăn mèo Ba Tư',
'Thức ăn Royal Canin chuyên cho mèo Ba Tư, giảm búi lông'),
('MeO Adult Seafood 7kg', 'meo-adult-seafood-7kg',
'Thức ăn MeO vị hải sản cho mèo trưởng thành. Giàu protein từ cá biển, bổ sung Taurine tốt cho mắt và tim.',
520000, 480000, 80, 'MEO-AS-7KG', 10, 'https://images.unsplash.com/photo-1589652717406-1c69efaf1ff8?w=500', 'published', TRUE,
'MeO Adult 7kg - Thức ăn mèo giá tốt',
'Thức ăn MeO vị hải sản giàu protein cho mèo khỏe mạnh'),
('Xương gặm Vegebrand Dental Stick', 'xuong-gam-vegebrand-dental-stick',
'Xương gặm làm sạch răng cho chó. Giúp giảm mảng bám, cao răng, thơm miệng. An toàn với thành phần từ thực vật.',
85000, 75000, 150, 'VGD-DS-01', 9, 'https://images.unsplash.com/photo-1615751072497-5f5169febe17?w=500', 'published', FALSE,
'Xương gặm Vegebrand - Chăm sóc răng miệng cho chó',
'Xương gặm làm sạch răng, an toàn từ thực vật cho chó'),
('Pate Whiskas vị Cá Ngừ 80g', 'pate-whiskas-ca-ngu-80g',
'Pate Whiskas thơm ngon vị cá ngừ cho mèo. Túi tiện lợi 80g, giàu protein và omega 3.',
18000, 15000, 300, 'WSK-TN-80G', 11, 'https://images.unsplash.com/photo-1591768575699-c071da0a4f1b?w=500', 'published', FALSE,
'Pate Whiskas 80g - Thức ăn ướt cho mèo',
'Pate Whiskas cá ngừ bổ dưỡng cho mèo mọi lứa tuổi'),
('Snack Ciao Churu Chicken 14g x 4', 'snack-ciao-churu-chicken-4-tubi',
'Snack dạng sốt Ciao Churu vị gà Nhật Bản. Mèo cực kỳ thích, bổ sung nước, tăng cường sức đề kháng.',
65000, 60000, 120, 'CIAO-CH-4T', 12, 'https://images.unsplash.com/photo-1548247416-ec66f4900b2e?w=500', 'published', TRUE,
'Ciao Churu - Snack dạng sốt cho mèo',
'Snack Ciao Churu Nhật Bản mèo cực thích, bổ sung nước'),
('Cát vệ sinh Minino 5L', 'cat-ve-sinh-minino-5l',
'Cát vệ sinh cao cấp cho mèo. Khử mùi hiệu quả, vón cục nhanh, ít bụi. Túi 5L tiện dụng.',
95000, 85000, 120, 'HYG-LT-5L', 5, 'https://images.unsplash.com/photo-1425082661705-1834bfd09dca?w=500', 'published', TRUE,
'Cát vệ sinh Minino 5L - Khử mùi hiệu quả',
'Cát vệ sinh cao cấp khử mùi tốt, vón cục nhanh cho mèo'),
('Sữa tắm Bio-Groom 473ml', 'sua-tam-bio-groom-473ml',
'Sữa tắm chuyên dụng cho chó mèo. Công thức dịu nhẹ không kích ứng, mùi hương thơm mát tự nhiên.',
180000, 165000, 80, 'HYG-SHP-473', 5, 'https://images.unsplash.com/photo-1601758228946-95b45afcc33e?w=500', 'published', FALSE,
'Sữa tắm Bio-Groom - Chăm sóc lông cho thú cưng',
'Sữa tắm cao cấp dịu nhẹ cho chó mèo, mùi hương thơm mát'),
('Vitamin tổng hợp Virbac', 'vitamin-tong-hop-virbac',
'Vitamin tổng hợp Virbac cho chó mèo. Tăng cường sức đề kháng, bổ sung dưỡng chất thiết yếu. Hộp 30 viên.',
280000, 260000, 70, 'HLTH-VIT-30', 6, 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500', 'published', FALSE,
'Vitamin Virbac - Bổ sung dinh dưỡng cho thú cưng',
'Vitamin tổng hợp tăng cường sức khỏe cho chó mèo')
ON DUPLICATE KEY UPDATE name=name;

-- Thêm liên hệ mẫu
INSERT INTO contacts (name, email, phone, subject, message, status, ip_address, created_at)
VALUES
('Nguyễn Văn A', 'nguyenvana@gmail.com', '0987654321', 'Hỏi về dịch vụ tắm spa cho chó', 
'Xin chào, shop có dịch vụ tắm spa cho chó Alaska không? Giá bao nhiêu ạ? Cảm ơn!', 
'read', '192.168.1.20', NOW() - INTERVAL 5 DAY),
('Trần Thị B', 'tranthib@gmail.com', '0976543210', 'Thắc mắc về sản phẩm Royal Canin',
'Cho mình hỏi Royal Canin Persian Adult có size 10kg không? Mình muốn mua số lượng lớn.',
'replied', '192.168.1.21', NOW() - INTERVAL 3 DAY),
('Lê Văn C', 'levanc@gmail.com', '0965432109', 'Tư vấn thức ăn cho mèo con',
'Mèo mình 2 tháng tuổi, nên cho ăn loại thức ăn nào? Shop có giao hàng tận nhà không?',
'unread', '192.168.1.22', NOW() - INTERVAL 1 DAY),
('Phạm Thị D', 'phamthid@gmail.com', '0954321098', 'Đặt hàng số lượng lớn',
'Shop có hỗ trợ giá sỉ không? Mình muốn đặt 50 túi thức ăn Pedigree 3kg.',
'unread', '192.168.1.23', NOW() - INTERVAL 3 HOUR)
ON DUPLICATE KEY UPDATE name=name;

-- =====================================================
-- SETUP COMPLETE
-- =====================================================
-- Database petcare_db has been created with:
-- - All required tables
-- - Admin user: admin@example.com / admin123
-- - Staff user: staff@petcare.com / staff123  
-- - 5 customers with password: customer123
-- - 10+ products with categories
-- - Sample posts, comments, contacts
-- - Default FAQs, pages, and home page content
-- =====================================================