-- MySQL schema for PetCare (utf8mb4)
-- Run in phpMyAdmin or mysql client

CREATE DATABASE IF NOT EXISTS `petcare_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `petcare_db`;

SET NAMES utf8mb4;

SET time_zone = '+00:00';

-- Users
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
) ENGINE = InnoDB;

-- Posts (News/Articles)
CREATE TABLE IF NOT EXISTS posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_user_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    summary TEXT NULL,
    content_html MEDIUMTEXT NULL,
    cover_image_url VARCHAR(255) NULL,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    published_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_posts_author FOREIGN KEY (author_user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB;

CREATE INDEX idx_posts_status_published_at ON posts (status, published_at);

-- Post Comments / Ratings
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
        'rejected',
        'spam'
    ) NOT NULL DEFAULT 'pending',
    ip_address VARCHAR(64) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE,
    CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB;

CREATE INDEX idx_post_comments_status ON post_comments (status);