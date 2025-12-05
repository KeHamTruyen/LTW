-- Demo data for Posts module testing
-- Run this AFTER importing schema.sql and creating admin user

USE `petcare_db`;

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
        '<h2>Giữ cho thú cưng luôn mát mẻ</h2>        http://localhost/LTW/public/init-db.php
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
    );

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
        3,
        'Bùi Văn G',
        'vang@example.com',
        'Spam comment test',
        NULL,
        'spam',
        '192.168.1.1',
        NOW()
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
    );

-- Update counts
UPDATE posts SET created_at = NOW() WHERE id = 1;