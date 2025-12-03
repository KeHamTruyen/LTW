-- Migration: Add missing tables for complete assignment requirements
-- Run this after schema.sql to add all required tables

USE `petcare_db`;

-- Categories (for products and potentially posts)
CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products
CREATE TABLE IF NOT EXISTS products (
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
    gallery_images TEXT NULL COMMENT 'JSON array of image URLs',
    status ENUM('draft', 'published', 'out_of_stock') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    INDEX idx_featured (featured),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Shopping Cart Items
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders
CREATE TABLE IF NOT EXISTS orders (
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
    INDEX idx_order_number (order_number),
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_created (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    product_name VARCHAR(255) NOT NULL COMMENT 'Snapshot at time of order',
    product_price DECIMAL(10,2) NOT NULL COMMENT 'Price at time of order',
    quantity INT UNSIGNED NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    INDEX idx_order (order_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contacts (customer contact submissions)
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
    FOREIGN KEY (replied_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pages (for managing page content)
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
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FAQ
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
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- About Page (single row table)
CREATE TABLE IF NOT EXISTS about_page (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) DEFAULT 'About us',
    description TEXT,
    mission TEXT,
    vision TEXT,
    image VARCHAR(255),
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add category_id to posts if not exists (for categorizing posts)
-- Check if column exists before adding
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_SCHEMA = DATABASE() 
                   AND TABLE_NAME = 'posts' 
                   AND COLUMN_NAME = 'category_id');

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE posts ADD COLUMN category_id BIGINT UNSIGNED NULL',
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add index for category_id if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
                     WHERE TABLE_SCHEMA = DATABASE() 
                     AND TABLE_NAME = 'posts' 
                     AND INDEX_NAME = 'idx_category');

SET @sql = IF(@index_exists = 0,
    'ALTER TABLE posts ADD INDEX idx_category (category_id)',
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add foreign key constraint if it doesn't exist
SET @fk_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                  WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = 'posts' 
                  AND CONSTRAINT_NAME = 'fk_posts_category');

SET @sql = IF(@fk_exists = 0 AND @col_exists = 0,
    'ALTER TABLE posts ADD CONSTRAINT fk_posts_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL',
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Seed initial data

-- Insert default categories
INSERT INTO categories (name, slug, description) VALUES
('Thức ăn', 'thuc-an', 'Thức ăn cho thú cưng'),
('Đồ chơi', 'do-choi', 'Đồ chơi cho thú cưng'),
('Phụ kiện', 'phu-kien', 'Phụ kiện cho thú cưng')
ON DUPLICATE KEY UPDATE name=name;

-- Insert default FAQ if empty
INSERT INTO faqs (question, answer, display_order) VALUES
('Làm thế nào để liên hệ với chúng tôi?', 'Bạn có thể liên hệ với chúng tôi qua form liên hệ trên website hoặc email support@petcare.com', 1),
('Bạn có giao hàng tận nơi không?', 'Có, chúng tôi giao hàng trên toàn quốc. Phí vận chuyển sẽ được tính dựa trên địa chỉ giao hàng.', 2),
('Thời gian giao hàng là bao lâu?', 'Thời gian giao hàng từ 2-5 ngày làm việc tùy thuộc vào khu vực.', 3)
ON DUPLICATE KEY UPDATE question=question;

-- Insert default about page if empty
INSERT INTO about_page (title, description, mission, vision)
SELECT 'Về chúng tôi', 
       'Chúng tôi là công ty chuyên cung cấp các dịch vụ và sản phẩm chăm sóc thú cưng chất lượng cao.',
       'Sứ mệnh của chúng tôi là mang lại sức khỏe và hạnh phúc cho thú cưng của bạn.',
       'Trở thành công ty hàng đầu trong lĩnh vực chăm sóc thú cưng tại Việt Nam.'
FROM DUAL WHERE NOT EXISTS (SELECT * FROM about_page);

-- Insert default pages
INSERT INTO pages (slug, title, content_html, status) VALUES
('pricing', 'Bảng giá', '<h2>Bảng giá dịch vụ</h2><p>Nội dung bảng giá sẽ được cập nhật...</p>', 'published'),
('service', 'Dịch vụ', '<h2>Dịch vụ của chúng tôi</h2><p>Nội dung dịch vụ sẽ được cập nhật...</p>', 'published')
ON DUPLICATE KEY UPDATE title=title;

