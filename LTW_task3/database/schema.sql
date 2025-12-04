DROP DATABASE IF EXISTS LTW_DB;

CREATE DATABASE IF NOT EXISTS LTW_DB;

USE LTW_DB;

DROP TABLE IF EXISTS PAYMENT;

DROP TABLE IF EXISTS ORDER_DETAIL;

DROP TABLE IF EXISTS Orders;

DROP TABLE IF EXISTS CART_ITEM;

DROP TABLE IF EXISTS Cart;

DROP TABLE IF EXISTS PRODUCT;

DROP TABLE IF EXISTS USER;

CREATE TABLE IF NOT EXISTS USER (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('customer', 'staff', 'admin') DEFAULT 'customer',
    avatar VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS PRODUCT (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    stock_quantity INT NOT NULL DEFAULT 0,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Cart (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    USER_id INT UNIQUE NOT NULL,
    FOREIGN KEY (USER_id) REFERENCES USER (ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Orders (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    USER_id INT NOT NULL,
    Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Status ENUM(
        'pending',
        'processing',
        'shipped',
        'delivered',
        'cancelled'
    ) DEFAULT 'pending',
    total_amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (USER_id) REFERENCES USER (ID) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS CART_ITEM (
    cart_id INT NOT NULL,
    PRODUCT_id INT NOT NULL,
    Quantity INT NOT NULL DEFAULT 1,
    PRIMARY KEY (cart_id, PRODUCT_id),
    FOREIGN KEY (cart_id) REFERENCES Cart (ID) ON DELETE CASCADE,
    FOREIGN KEY (PRODUCT_id) REFERENCES PRODUCT (ID) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS ORDER_DETAIL (
    order_id INT NOT NULL,
    PRODUCT_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_order DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (order_id, PRODUCT_id),
    FOREIGN KEY (order_id) REFERENCES Orders (ID) ON DELETE CASCADE,
    FOREIGN KEY (PRODUCT_id) REFERENCES PRODUCT (ID) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS PAYMENT (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT UNIQUE NOT NULL,
    method VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders (ID) ON DELETE RESTRICT
);

-- 1. Bảng USER
INSERT INTO
    USER (
        ID,
        name,
        email,
        password,
        phone,
        role
    )
VALUES (
        1,
        'Admin PetCare',
        'admin@petcare.com',
        '$2y$10$hashed_admin_password_1',
        '0900111222',
        'admin'
    ),
    (
        2,
        'Le Tan Phong',
        'staff@petcare.com',
        '$2y$10$hashed_staff_password_2',
        '0900333444',
        'staff'
    ),
    (
        3,
        'Khach Dat Lich',
        'khach1@gmail.com',
        '$2y$10$hashed_cust_password_3',
        '0910555666',
        'customer'
    ),
    (
        4,
        'Khach Mua Hang',
        'khach2@gmail.com',
        '$2y$10$hashed_cust_password_4',
        '0910777888',
        'customer'
    );

-- 2. Bảng PRODUCT (PetCare Services & PRODUCTs)
INSERT INTO
    PRODUCT (
        ID,
        name,
        price,
        description,
        image,
        stock_quantity
    )
VALUES (
        101,
        'Thuc An Hat Cho Lon 1kg',
        250000.00,
        'Thức ăn giàu Protein cho chó trên 1 năm tuổi.',
        'images/food.jpg',
        50
    ),
    (
        102,
        'Dich Vu Grooming Co Ban',
        350000.00,
        'Cắt tỉa, tắm, sấy, vệ sinh tai/mắt.',
        'images/grooming.jpg',
        999
    ), -- Dịch vụ không giới hạn tồn kho vật lý
    (
        103,
        'Kham Suc Khoe Tong Quat',
        500000.00,
        'Khám tổng thể, tư vấn dinh dưỡng cho thú cưng.',
        'images/vet.jpg',
        999
    );

-- 3. Bảng Cart và CART_ITEM
-- Tạo Giỏ hàng cho Khách hàng 1 (ID=3)
INSERT INTO Cart (ID, USER_id) VALUES (50, 3);
-- Khách hàng 3 muốn đặt 1 lịch Grooming (ID=102) và mua 1 Thức ăn (ID=101)
INSERT INTO
    CART_ITEM (cart_id, PRODUCT_id, Quantity)
VALUES (50, 102, 1);

INSERT INTO
    CART_ITEM (cart_id, PRODUCT_id, Quantity)
VALUES (50, 101, 1);

-- 4. Bảng Orders và ORDER_DETAIL
-- Đơn hàng 1001: Mua hàng vật lý đã giao (Khách hàng 4)
INSERT INTO
    Orders (
        ID,
        USER_id,
        Date,
        Status,
        total_amount
    )
VALUES (
        1001,
        4,
        NOW(),
        'delivered',
        250000.00
    );
-- Sản phẩm 101 (Thức ăn)
INSERT INTO
    ORDER_DETAIL (
        order_id,
        PRODUCT_id,
        quantity,
        price_at_order
    )
VALUES (1001, 101, 1, 250000.00);

-- Đơn hàng 1002: Lịch hẹn đang xử lý (Khách hàng 3) - Tổng tiền 500,000
INSERT INTO
    Orders (
        ID,
        USER_id,
        Date,
        Status,
        total_amount
    )
VALUES (
        1002,
        3,
        DATE_SUB(NOW(), INTERVAL 1 DAY),
        'processing',
        500000.00
    );
-- Chi tiết: Dịch vụ Khám sức khỏe (ID=103)
INSERT INTO
    ORDER_DETAIL (
        order_id,
        PRODUCT_id,
        quantity,
        price_at_order
    )
VALUES (1002, 103, 1, 500000.00);

-- 5. Bảng PAYMENT
-- Thanh toán cho Đơn hàng 1001
INSERT INTO
    PAYMENT (ID, order_id, method, amount)
VALUES (2001, 1001, 'COD', 250000.00);

-- Thanh toán cho Lịch hẹn 1002
INSERT INTO
    PAYMENT (ID, order_id, method, amount)
VALUES (
        2002,
        1002,
        'Bank Transfer',
        500000.00
    );