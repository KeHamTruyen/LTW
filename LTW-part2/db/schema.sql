-- Database schema for LTW Part 2 (About + FAQ)
CREATE DATABASE IF NOT EXISTS ltw CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ltw;

-- about_page single row table
CREATE TABLE IF NOT EXISTS about_page (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) DEFAULT 'About us',
  description TEXT,
  mission TEXT,
  vision TEXT,
  image VARCHAR(255),
  updated_at TIMESTAMP NULL DEFAULT NULL
);

-- seed one row if empty
INSERT INTO about_page (title, description, mission, vision)
SELECT 'Company name', 'This is the about description.', 'Mission text', 'Vision text'
FROM DUAL WHERE NOT EXISTS (SELECT * FROM about_page);

CREATE TABLE IF NOT EXISTS faq (
  id INT PRIMARY KEY AUTO_INCREMENT,
  question VARCHAR(255) NOT NULL,
  answer TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- sample FAQ
INSERT INTO faq (question, answer) VALUES
('How to contact support?', 'You can contact us via email.'),
('Where are you located?', 'We are located in HCMC.');
