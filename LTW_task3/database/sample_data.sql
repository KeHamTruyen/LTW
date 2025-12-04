USE LTW_DB;
-- 1. Bảng User
INSERT INTO
    User (
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

-- 2. Bảng Product (PetCare Services & Products)
INSERT INTO
    Product (
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

-- 3. Bảng Cart và Cart_Item
-- Tạo Giỏ hàng cho Khách hàng 1 (ID=3)
INSERT INTO Cart (ID, user_id) VALUES (50, 3);
-- Khách hàng 3 muốn đặt 1 lịch Grooming (ID=102) và mua 1 Thức ăn (ID=101)
INSERT INTO
    Cart_Item (cart_id, product_id, Quantity)
VALUES (50, 102, 1);

INSERT INTO
    Cart_Item (cart_id, product_id, Quantity)
VALUES (50, 101, 1);

-- 4. Bảng Orders và Order_Detail
-- Đơn hàng 1001: Mua hàng vật lý đã giao (Khách hàng 4)
INSERT INTO
    Orders (
        ID,
        user_id,
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
    Order_Detail (
        order_id,
        product_id,
        quantity,
        price_at_order
    )
VALUES (1001, 101, 1, 250000.00);

-- Đơn hàng 1002: Lịch hẹn đang xử lý (Khách hàng 3) - Tổng tiền 500,000
INSERT INTO
    Orders (
        ID,
        user_id,
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
    Order_Detail (
        order_id,
        product_id,
        quantity,
        price_at_order
    )
VALUES (1002, 103, 1, 500000.00);

-- 5. Bảng Payment
-- Thanh toán cho Đơn hàng 1001
INSERT INTO
    Payment (ID, order_id, method, amount)
VALUES (2001, 1001, 'COD', 250000.00);

-- Thanh toán cho Lịch hẹn 1002
INSERT INTO
    Payment (ID, order_id, method, amount)
VALUES (
        2002,
        1002,
        'Bank Transfer',
        500000.00
    );