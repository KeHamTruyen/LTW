-- Migration: Add home_page table for managing home page content
-- Run this to add home page management feature

USE `petcare_db`;

-- Home Page 
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
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default home page content if empty
INSERT INTO home_page (
    hero_title, hero_subtitle, hero_button_text, hero_image_url,
    company_name, company_phone, company_email,
    service_title, service_subtitle, service_combos,
    about_title, about_subtitle, about_description, about_button_text, about_image_url,
    products_title, products_subtitle, products_button_text,
    posts_title, posts_subtitle
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
FROM DUAL WHERE NOT EXISTS (SELECT * FROM home_page);



