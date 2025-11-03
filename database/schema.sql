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