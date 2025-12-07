-- =====================================================
-- SEED SAMPLE DATA FOR PETCARE_DB
-- =====================================================
-- File này chứa dữ liệu mẫu đầy đủ cho tất cả các bảng
-- Chạy sau khi đã import complete_database.sql
-- =====================================================

USE `petcare_db`;

-- =====================================================
-- 1. USERS (Thêm user mẫu)
-- =====================================================

INSERT INTO users (name, email, phone, avatar_url, password_hash, role, status, created_at)
VALUES 
-- Admin (password: admin123)
('Administrator', 'admin@example.com', '0901234567', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'admin', 'active', NOW() - INTERVAL 30 DAY),

-- Staff (password: admin123)
('Nguyễn Văn Staff', 'staff@petcare.com', '0902345678', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'staff', 'active', NOW() - INTERVAL 25 DAY),

-- Customers (password: admin123)
('Trần Thị Lan', 'lan.tran@gmail.com', '0903456789', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'customer', 'active', NOW() - INTERVAL 20 DAY),
('Lê Văn Hùng', 'hung.le@gmail.com', '0904567890', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'customer', 'active', NOW() - INTERVAL 15 DAY),
('Phạm Minh Tuấn', 'tuan.pham@gmail.com', '0905678901', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'customer', 'active', NOW() - INTERVAL 10 DAY),
('Hoàng Thu Hà', 'ha.hoang@gmail.com', '0906789012', NULL, '$2y$10$Qn.Mqd0S29Qb2h56f6LpiuJhuKw/VmfeLa4qV3p9yCgfDKu0B2I/O', 'customer', 'active', NOW() - INTERVAL 5 DAY)
ON DUPLICATE KEY UPDATE name=name;

-- =====================================================
-- 2. CATEGORIES (Cập nhật và thêm mới)
-- =====================================================

INSERT INTO categories (name, slug, description, parent_id) VALUES
-- Main categories
('Thức ăn cho chó', 'thuc-an-cho-cho', 'Thức ăn dinh dưỡng cho chó các loại', NULL),
('Thức ăn cho mèo', 'thuc-an-cho-meo', 'Thức ăn dinh dưỡng cho mèo các loại', NULL),
('Đồ chơi thú cưng', 'do-choi-thu-cung', 'Đồ chơi vui chơi giải trí cho thú cưng', NULL),
('Phụ kiện chó mèo', 'phu-kien-cho-meo', 'Phụ kiện cần thiết cho thú cưng', NULL),
('Vệ sinh & Chăm sóc', 've-sinh-cham-soc', 'Sản phẩm vệ sinh và chăm sóc thú cưng', NULL),
('Sức khỏe & Y tế', 'suc-khoe-y-te', 'Vitamin, thuốc và sản phẩm y tế cho thú cưng', NULL),

-- Sub categories for Thức ăn chó
('Thức ăn hạt', 'thuc-an-hat-cho', 'Thức ăn khô dạng hạt cho chó', 1),
('Thức ăn ướt', 'thuc-an-uot-cho', 'Thức ăn đóng hộp, pate cho chó', 1),
('Snack & Bánh thưởng', 'snack-banh-thuong-cho', 'Bánh thưởng, snack cho chó', 1),

-- Sub categories for Thức ăn mèo
('Thức ăn hạt cho mèo', 'thuc-an-hat-meo', 'Thức ăn khô dạng hạt cho mèo', 2),
('Pate & Thức ăn ướt', 'pate-thuc-an-uot-meo', 'Pate, thức ăn ướt cho mèo', 2),
('Snack cho mèo', 'snack-cho-meo', 'Bánh thưởng, snack cho mèo', 2)
ON DUPLICATE KEY UPDATE description=VALUES(description);

-- =====================================================
-- 3. PRODUCTS (Sản phẩm mẫu)
-- =====================================================

INSERT INTO products (name, slug, description, price, sale_price, stock_quantity, sku, category_id, image_url, status, featured, meta_title, meta_description)
VALUES
-- Thức ăn cho chó
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

('Ganador Adult Salmon 20kg', 'ganador-adult-salmon-20kg',
'Thức ăn cao cấp Ganador vị cá hồi cho chó trưởng thành. Omega 3, 6 giúp da lông khỏe đẹp, tăng cường hệ miễn dịch.',
2100000, NULL, 30, 'GND-AS-20KG', 7, 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=500', 'published', FALSE,
'Ganador Adult Salmon 20kg - Thức ăn cao cấp cho chó',
'Thức ăn Ganador cao cấp với cá hồi giàu Omega 3,6 giúp chó khỏe mạnh'),

('Pate Cesar vị Gà & Gan 100g', 'pate-cesar-ga-gan-100g',
'Pate thơm ngon Cesar vị gà và gan cho chó. Đóng gói tiện lợi 100g, thích hợp mix cùng thức ăn khô hoặc ăn riêng.',
35000, 32000, 200, 'CSR-CL-100G', 8, 'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=500', 'published', FALSE,
'Pate Cesar 100g - Thức ăn ướt cho chó',
'Pate Cesar thơm ngon bổ dưỡng cho chó mọi lứa tuổi'),

('Xương gặm Vegebrand Dental Stick', 'xuong-gam-vegebrand-dental-stick',
'Xương gặm làm sạch răng cho chó. Giúp giảm mảng bám, cao răng, thơm miệng. An toàn với thành phần từ thực vật.',
85000, 75000, 150, 'VGD-DS-01', 9, 'https://images.unsplash.com/photo-1615751072497-5f5169febe17?w=500', 'published', FALSE,
'Xương gặm Vegebrand - Chăm sóc răng miệng cho chó',
'Xương gặm làm sạch răng, an toàn từ thực vật cho chó'),

-- Thức ăn cho mèo
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

-- Đồ chơi
('Bóng cao su gai massage', 'bong-cao-su-gai-massage',
'Bóng chơi bằng cao su có gai massage cho chó. An toàn, không độc hại, giúp chó vận động và làm sạch răng.',
55000, 45000, 100, 'TOY-BRB-01', 3, 'https://images.unsplash.com/photo-1535930891776-0c2dfb7fda1a?w=500', 'published', FALSE,
'Bóng cao su massage - Đồ chơi cho chó',
'Bóng chơi an toàn cho chó, massage nướu và làm sạch răng'),

('Cần câu lông vũ cho mèo', 'can-cau-long-vu-cho-meo',
'Đồ chơi cần câu có lông vũ kích thích bản năng săn mồi của mèo. Giúp mèo vận động, giảm stress.',
45000, 38000, 80, 'TOY-FRD-01', 3, 'https://images.unsplash.com/photo-1545529468-42764ef8c85f?w=500', 'published', FALSE,
'Cần câu lông vũ - Đồ chơi cho mèo',
'Đồ chơi kích thích bản năng săn mồi, giúp mèo vận động'),

('Bóng chuột nhồi bông cho mèo', 'bong-chuot-nhoi-bong',
'Đồ chơi chuột nhồi bông mềm mại an toàn cho mèo. Có tiếng kêu và mùi catnip hấp dẫn.',
35000, 30000, 150, 'TOY-MST-01', 3, 'https://images.unsplash.com/photo-1611003228941-98852ba62227?w=500', 'published', FALSE,
'Chuột nhồi bông - Đồ chơi an toàn cho mèo',
'Đồ chơi chuột mềm mại với catnip hấp dẫn cho mèo'),

-- Phụ kiện
('Vòng cổ dây dắt chó size M', 'vong-co-day-dat-cho-size-m',
'Bộ vòng cổ và dây dắt bền chắc cho chó size vừa (10-25kg). Có thể điều chỉnh độ rộng, móc khóa an toàn.',
120000, 99000, 70, 'ACC-CL-M', 4, 'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=500', 'published', FALSE,
'Vòng cổ dây dắt size M - Phụ kiện chó',
'Bộ vòng cổ dây dắt bền đẹp cho chó giống vừa'),

('Bát ăn inox đôi có chân đế', 'bat-an-inox-doi-co-chan-de',
'Bát ăn đôi bằng inox 304 với chân đế chống đổ. Dễ vệ sinh, bền đẹp, thích hợp cho chó mèo.',
150000, 135000, 90, 'ACC-BWL-DB', 4, 'https://images.unsplash.com/photo-1591856419786-62437a1b2e55?w=500', 'published', FALSE,
'Bát ăn inox đôi - Phụ kiện cho chó mèo',
'Bát ăn inox cao cấp với chân đế chống đổ, dễ vệ sinh'),

('Nhà lều vải cho mèo', 'nha-leu-vai-cho-meo',
'Nhà lều vải mềm ấm áp cho mèo. Có thể gấp gọn, dễ di chuyển. Kích thước 40x40x40cm.',
250000, 220000, 40, 'ACC-TNT-01', 4, 'https://images.unsplash.com/photo-1598439210625-5067c578f3f6?w=500', 'published', FALSE,
'Nhà lều vải - Phụ kiện cho mèo',
'Nhà lều ấm áp cho mèo nghỉ ngơi, có thể gấp gọn'),

('Túi vận chuyển thú cưng size M', 'tui-van-chuyen-thu-cung-size-m',
'Túi vận chuyển chó mèo bằng vải lưới thoáng khí. Size M cho thú cưng 5-10kg. Có dây đeo vai tiện lợi.',
320000, 280000, 50, 'ACC-CRR-M', 4, 'https://images.unsplash.com/photo-1504208434309-cb69f4fe52b0?w=500', 'published', TRUE,
'Túi vận chuyển size M - Phụ kiện thú cưng',
'Túi vận chuyển thoáng khí an toàn cho chó mèo'),

-- Vệ sinh & Chăm sóc
('Cát vệ sinh Minino 5L', 'cat-ve-sinh-minino-5l',
'Cát vệ sinh cao cấp cho mèo. Khử mùi hiệu quả, vón cục nhanh, ít bụi. Túi 5L tiện dụng.',
95000, 85000, 120, 'HYG-LT-5L', 5, 'https://images.unsplash.com/photo-1425082661705-1834bfd09dca?w=500', 'published', TRUE,
'Cát vệ sinh Minino 5L - Khử mùi hiệu quả',
'Cát vệ sinh cao cấp khử mùi tốt, vón cục nhanh cho mèo'),

('Khay vệ sinh cho mèo có nắp', 'khay-ve-sinh-cho-meo-co-nap',
'Khay vệ sinh có nắp đậy giảm mùi, kèm xẻng múc. Size lớn 50x40x40cm phù hợp mọi giống mèo.',
280000, 250000, 60, 'HYG-LB-L', 5, 'https://images.unsplash.com/photo-1589883661923-6476cb0ae9f2?w=500', 'published', FALSE,
'Khay vệ sinh có nắp - Phụ kiện cho mèo',
'Khay vệ sinh kín đáo, khử mùi hiệu quả cho mèo'),

('Sữa tắm Bio-Groom 473ml', 'sua-tam-bio-groom-473ml',
'Sữa tắm chuyên dụng cho chó mèo. Công thức dịu nhẹ không kích ứng, mùi hương thơm mát tự nhiên.',
180000, 165000, 80, 'HYG-SHP-473', 5, 'https://images.unsplash.com/photo-1601758228946-95b45afcc33e?w=500', 'published', FALSE,
'Sữa tắm Bio-Groom - Chăm sóc lông cho thú cưng',
'Sữa tắm cao cấp dịu nhẹ cho chó mèo, mùi hương thơm mát'),

('Lược chải lông mềm cho chó', 'luoc-chai-long-mem-cho-cho',
'Lược chải lông có đầu cong bảo vệ da, giúp loại bỏ lông rụng hiệu quả cho chó lông dài.',
75000, 65000, 100, 'HYG-BRS-01', 5, 'https://images.unsplash.com/photo-1581888227599-779811939961?w=500', 'published', FALSE,
'Lược chải lông - Dụng cụ chăm sóc chó',
'Lược chải lông an toàn, loại bỏ lông rụng hiệu quả'),

-- Sức khỏe & Y tế
('Vitamin tổng hợp Virbac', 'vitamin-tong-hop-virbac',
'Vitamin tổng hợp Virbac cho chó mèo. Tăng cường sức đề kháng, bổ sung dưỡng chất thiết yếu. Hộp 30 viên.',
280000, 260000, 70, 'HLTH-VIT-30', 6, 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500', 'published', FALSE,
'Vitamin Virbac - Bổ sung dinh dưỡng cho thú cưng',
'Vitamin tổng hợp tăng cường sức khỏe cho chó mèo'),

('Thuốc tẩy giun nội soi Fenbendazole', 'thuoc-tay-giun-fenbendazole',
'Thuốc tẩy giun nội soi an toàn cho chó mèo. Hiệu quả với giun đũa, giun móc, giun dây. Hộp 10 viên.',
120000, 110000, 90, 'HLTH-DWM-10', 6, 'https://images.unsplash.com/photo-1585245940866-3b2fcaa82670?w=500', 'published', FALSE,
'Thuốc tẩy giun - Chăm sóc sức khỏe thú cưng',
'Thuốc tẩy giun an toàn hiệu quả cho chó mèo'),

('Dung dịch vệ sinh tai Epi-Otic', 'dung-dich-ve-sinh-tai-epi-otic',
'Dung dịch vệ sinh tai chuyên dụng cho chó mèo. Làm sạch nhẹ nhàng, phòng ngừa viêm tai.',
160000, 145000, 60, 'HLTH-EAR-125', 6, 'https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?w=500', 'published', FALSE,
'Dung dịch vệ sinh tai - Y tế thú cưng',
'Vệ sinh tai an toàn phòng ngừa viêm nhiễm cho thú cưng')
ON DUPLICATE KEY UPDATE name=name;

-- =====================================================
-- 4. POSTS (Thêm bài viết mẫu)
-- =====================================================

INSERT INTO posts (author_user_id, title, slug, summary, content_html, category_id, status, published_at, created_at)
VALUES
(1, 'Top 10 giống chó cảnh được yêu thích nhất tại Việt Nam 2024', 'top-10-giong-cho-canh-yeu-thich-2024',
'Khám phá 10 giống chó cảnh phổ biến và được nhiều gia đình Việt Nam lựa chọn nuôi. Từ Poodle, Corgi đến Golden Retriever.',
'<h2>1. Poodle (Chó Poodle)</h2>
<p>Poodle là giống chó thông minh, dễ huấn luyện và không rụng lông nhiều. Có 3 size: Toy, Mini và Standard.</p>
<h3>Đặc điểm nổi bật:</h3>
<ul>
<li>Thông minh, học nhanh</li>
<li>Lông xoăn không gây dị ứng</li>
<li>Thân thiện với trẻ em</li>
<li>Cần chải lông thường xuyên</li>
</ul>

<h2>2. Corgi (Chó Corgi)</h2>
<p>Corgi với đôi chân ngắn đáng yêu là giống chó được giới trẻ yêu thích. Tính cách vui vẻ, năng động.</p>
<h3>Ưu điểm:</h3>
<ul>
<li>Ngoại hình đáng yêu, dễ thương</li>
<li>Thông minh, trung thành</li>
<li>Thích hợp nuôi trong chung cư</li>
<li>Giá: 15-30 triệu VNĐ</li>
</ul>

<h2>3. Golden Retriever</h2>
<p>Golden là giống chó gia đình lý tưởng với tính cách hiền lành, trung thành tuyệt đối.</p>

<h2>4. Husky</h2>
<p>Husky với vẻ ngoài giống sói, đôi mắt xanh đặc biệt là giống chó được nhiều người mơ ước.</p>

<h2>5. Shiba Inu</h2>
<p>Shiba từ Nhật Bản với khuôn mặt "cười" đặc trưng, độc lập và thông minh.</p>

<p><em>Xem tiếp các giống còn lại: Chihuahua, Pug, Phốc sóc, Bulldog Pháp, Beagle...</em></p>', 
4, 'published', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

(1, '7 Dấu hiệu nhận biết thú cưng bị bệnh cần đưa đi khám ngay', 'dau-hieu-thu-cung-bi-benh',
'Làm thế nào để biết chó mèo của bạn đang bị bệnh? 7 dấu hiệu cảnh báo bạn cần đưa thú cưng đi bác sĩ thú y ngay.',
'<h2>1. Mất cảm giác thèm ăn đột ngột</h2>
<p>Nếu thú cưng từ chối ăn uống trong 24h trở lên, đây là dấu hiệu nghiêm trọng cần khám ngay.</p>

<h2>2. Nôn mửa liên tục</h2>
<p>Nôn 1-2 lần có thể do ăn cỏ hoặc thức ăn không phù hợp. Nhưng nôn nhiều lần, có máu cần gặp bác sĩ.</p>

<h2>3. Tiêu chảy kéo dài</h2>
<p>Phân lỏng, có máu hoặc nhầy là dấu hiệu nhiễm trùng đường ruột, ký sinh trùng.</p>

<h2>4. Khó thở, thở gấp bất thường</h2>
<p>Thở nhanh, há mồm thở ở mèo (không phải do nóng) là dấu hiệu tim phổi, cần cấp cứu.</p>

<h2>5. Uống nước và đi tiểu nhiều</h2>
<p>Có thể là dấu hiệu tiểu đường, suy thận, bệnh nội tiết.</p>

<h2>6. Sưng vùng bụng</h2>
<p>Bụng to lên nhanh chóng có thể do tích nước, khối u, hoặc xoắn dạ dày (nguy hiểm).</p>

<h2>7. Thay đổi hành vi đột ngột</h2>
<p>Ẩn nấp, sợ hãi, hung hãn bất thường, hoặc lờ đờ có thể do đau đớn hoặc bệnh lý.</p>

<p><strong>Lưu ý:</strong> Khi có bất kỳ dấu hiệu nào trên, hãy liên hệ bác sĩ thú y ngay. Phát hiện sớm giúp điều trị hiệu quả hơn.</p>',
4, 'published', NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 7 DAY),

(1, 'Hướng dẫn chọn cát vệ sinh cho mèo phù hợp nhất', 'huong-dan-chon-cat-ve-sinh-cho-meo',
'So sánh các loại cát vệ sinh: cát vón, cát đậu, cát đá, cát giấy. Ưu nhược điểm và cách chọn phù hợp với mèo nhà bạn.',
'<h2>Các loại cát vệ sinh phổ biến</h2>

<h3>1. Cát vón (Clumping Litter)</h3>
<p><strong>Ưu điểm:</strong></p>
<ul>
<li>Vón cục nhanh, dễ dọn</li>
<li>Khử mùi tốt</li>
<li>Tiết kiệm (chỉ bỏ cục bẩn)</li>
</ul>
<p><strong>Nhược điểm:</strong></p>
<ul>
<li>Có bụi (chọn loại ít bụi)</li>
<li>Nguy hiểm nếu mèo con ăn phải</li>
</ul>

<h3>2. Cát đậu (Tofu Litter)</h3>
<p><strong>Ưu điểm:</strong></p>
<ul>
<li>Từ thiên nhiên, an toàn</li>
<li>Vứt vào bồn cầu được</li>
<li>Không bụi, mùi dễ chịu</li>
</ul>
<p><strong>Nhược điểm:</strong></p>
<ul>
<li>Giá cao hơn</li>
<li>Khả năng khử mùi trung bình</li>
</ul>

<h3>3. Cát đá (Crystal Litter)</h3>
<p><strong>Ưu điểm:</strong></p>
<ul>
<li>Khử mùi cực tốt</li>
<li>Dùng lâu (3-4 tuần)</li>
<li>Không bụi</li>
</ul>
<p><strong>Nhược điểm:</strong></p>
<ul>
<li>Giá đắt nhất</li>
<li>Không vón cục</li>
<li>Một số mèo không thích</li>
</ul>

<h3>4. Cát giấy (Paper Litter)</h3>
<p><strong>Ưu điểm:</strong></p>
<ul>
<li>An toàn cho mèo con</li>
<li>Không bụi, không dị ứng</li>
<li>Thân thiện môi trường</li>
</ul>
<p><strong>Nhược điểm:</strong></p>
<ul>
<li>Khử mùi yếu</li>
<li>Phải thay toàn bộ thường xuyên</li>
</ul>

<h2>Cách chọn cát phù hợp</h2>
<ol>
<li><strong>Mèo con dưới 3 tháng:</strong> Dùng cát giấy hoặc cát đậu (an toàn nếu ăn phải)</li>
<li><strong>Mèo trưởng thành:</strong> Cát vón hoặc cát đậu (tiện lợi, kinh tế)</li>
<li><strong>Nhà có nhiều mèo:</strong> Cát đá (khử mùi mạnh) hoặc cát vón</li>
<li><strong>Mèo dị ứng, hen:</strong> Cát đậu, cát giấy (không bụi)</li>
<li><strong>Ngân sách hạn chế:</strong> Cát vón (giá tốt, hiệu quả)</li>
</ol>

<h2>Mẹo sử dụng cát hiệu quả</h2>
<ul>
<li>Đổ cát dày 5-7cm để mèo gạt được</li>
<li>Múc bỏ phân và cục nước tiểu mỗi ngày</li>
<li>Thay toàn bộ cát 2-4 tuần/lần</li>
<li>Rửa sạch khay khi thay cát mới</li>
<li>Đặt khay ở nơi yên tĩnh, thoáng</li>
</ul>',
4, 'published', NOW() - INTERVAL 10 DAY, NOW() - INTERVAL 10 DAY),

(2, 'Chế độ dinh dưỡng cho chó con từ 2-12 tháng tuổi', 'che-do-dinh-duong-cho-cho-con',
'Hướng dẫn chi tiết lịch ăn, khẩu phần và loại thức ăn phù hợp cho chó con giai đoạn từ cai sữa đến trưởng thành.',
'<h2>2-3 tháng tuổi (vừa cai sữa)</h2>
<p><strong>Số bữa:</strong> 4 bữa/ngày</p>
<p><strong>Thức ăn:</strong> Thức ăn hạt cho chó con (Puppy), ngâm mềm với nước ấm</p>
<p><strong>Khẩu phần:</strong> 50-100g/ngày (tùy giống)</p>

<h2>3-6 tháng tuổi</h2>
<p><strong>Số bữa:</strong> 3 bữa/ngày</p>
<p><strong>Thức ăn:</strong> Thức ăn hạt Puppy (có thể cho ăn khô)</p>
<p><strong>Khẩu phần:</strong> 100-200g/ngày</p>
<p><strong>Bổ sung:</strong> Pate, thịt gà luộc (1-2 lần/tuần)</p>

<h2>6-12 tháng tuổi</h2>
<p><strong>Số bữa:</strong> 2 bữa/ngày</p>
<p><strong>Thức ăn:</strong> Thức ăn Puppy hoặc chuyển sang Adult (giống nhỏ 8-10 tháng, giống lớn 12 tháng)</p>
<p><strong>Khẩu phần:</strong> 200-400g/ngày (tùy cân nặng)</p>

<h2>Thực phẩm NÊN ăn</h2>
<ul>
<li>Thức ăn hạt chất lượng (Royal Canin, Ganador...)</li>
<li>Thịt gà, bò luộc (không gia vị)</li>
<li>Trứng gà luộc chín</li>
<li>Cơm hoặc khoai lang luộc (ít)</li>
<li>Rau củ: cà rốt, bí đỏ, bông cải</li>
</ul>

<h2>Thực phẩm TUYỆT ĐỐI KHÔNG cho ăn</h2>
<ul>
<li>Chocolate, ca cao</li>
<li>Hành tây, tỏi</li>
<li>Nho, nho khô</li>
<li>Xương gà (dễ vỡ, đâm thủng ruột)</li>
<li>Thức ăn cay, mặn, nhiều gia vị</li>
<li>Sữa bò (gây tiêu chảy)</li>
</ul>

<h2>Lưu ý quan trọng</h2>
<ol>
<li>Luôn để nước sạch cho chó uống</li>
<li>Không đổi thức ăn đột ngột (chuyển dần trong 7-10 ngày)</li>
<li>Theo dõi cân nặng, điều chỉnh khẩu phần</li>
<li>Tiêm phòng và tẩy giun đúng lịch</li>
<li>Bổ sung vitamin nếu bác sĩ khuyên</li>
</ol>',
5, 'published', NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 12 DAY)
ON DUPLICATE KEY UPDATE title=title;

-- =====================================================
-- 5. POST COMMENTS (Thêm comment mẫu cho bài mới)
-- =====================================================

INSERT INTO post_comments (post_id, user_id, author_name, author_email, content, rating, status, ip_address, created_at)
VALUES
(6, 3, 'Trần Thị Lan', 'lan.tran@gmail.com', 'Bài viết rất hữu ích! Nhà mình đang định nuôi Corgi, cảm ơn tác giả đã chia sẻ.', 5, 'approved', '192.168.1.10', NOW() - INTERVAL 2 DAY),
(6, 4, 'Lê Văn Hùng', 'hung.le@gmail.com', 'Golden Retriever là giống tốt nhất cho gia đình có trẻ em. Mình nuôi 2 năm rồi, rất hiền và thông minh.', 5, 'approved', '192.168.1.11', NOW() - INTERVAL 1 DAY),
(6, NULL, 'Nguyễn An', 'an.nguyen@gmail.com', 'Cho mình hỏi Poodle có rụng lông nhiều không ạ?', 4, 'pending', '192.168.1.12', NOW() - INTERVAL 5 HOUR),

(7, 5, 'Phạm Minh Tuấn', 'tuan.pham@gmail.com', 'Cảm ơn bài viết! Chó mình có triệu chứng mất cảm giác thèm ăn, mình sẽ đưa đi khám ngay.', 5, 'approved', '192.168.1.13', NOW() - INTERVAL 6 DAY),
(7, NULL, 'Mai Linh', 'linh.mai@gmail.com', 'Bài viết rất chi tiết và hữu ích. Mọi người nuôi thú cưng nên đọc để biết cách nhận biết bệnh.', 5, 'approved', '192.168.1.14', NOW() - INTERVAL 5 DAY),

(8, 6, 'Hoàng Thu Hà', 'ha.hoang@gmail.com', 'Nhà mình dùng cát đậu, mèo rất thích và dễ dọn. Cảm ơn tác giả đã so sánh rõ ràng!', 5, 'approved', '192.168.1.15', NOW() - INTERVAL 9 DAY),
(8, 3, 'Trần Thị Lan', 'lan.tran@gmail.com', 'Mình đang phân vân giữa cát vón và cát đậu, sau khi đọc bài này quyết định chọn cát đậu.', 4, 'approved', '192.168.1.16', NOW() - INTERVAL 8 DAY),

(9, 4, 'Lê Văn Hùng', 'hung.le@gmail.com', 'Chó con mình 3 tháng tuổi, đang ăn theo lịch này. Khỏe mạnh và phát triển tốt!', 5, 'approved', '192.168.1.17', NOW() - INTERVAL 11 DAY)
ON DUPLICATE KEY UPDATE author_name=author_name;

-- =====================================================
-- 6. CONTACTS (Liên hệ mẫu)
-- =====================================================

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
-- 7. ORDERS & ORDER ITEMS (Đơn hàng mẫu)
-- =====================================================

-- Order 1: Đơn hàng hoàn thành
INSERT INTO orders (order_number, user_id, customer_name, customer_email, customer_phone, shipping_address, total_amount, status, payment_method, payment_status, created_at)
VALUES 
('ORD-20241201-001', 3, 'Trần Thị Lan', 'lan.tran@gmail.com', '0903456789', 
'123 Nguyễn Văn Cừ, Quận 5, TP.HCM', 1699000, 'delivered', 'COD', 'paid', NOW() - INTERVAL 15 DAY);

INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal)
VALUES 
(1, 1, 'Royal Canin Medium Adult 10kg', 1150000, 1, 1150000),
(1, 8, 'Pate Whiskas vị Cá Ngừ 80g', 15000, 10, 150000),
(1, 15, 'Bát ăn inox đôi có chân đế', 135000, 1, 135000),
(1, 5, 'Xương gặm Vegebrand Dental Stick', 75000, 3, 225000);

-- Order 2: Đơn hàng đang xử lý
INSERT INTO orders (order_number, user_id, customer_name, customer_email, customer_phone, shipping_address, total_amount, status, payment_method, payment_status, created_at)
VALUES 
('ORD-20241203-002', 4, 'Lê Văn Hùng', 'hung.le@gmail.com', '0904567890',
'456 Lê Lợi, Quận 1, TP.HCM', 830000, 'processing', 'Bank Transfer', 'paid', NOW() - INTERVAL 5 DAY);

INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal)
VALUES
(2, 7, 'MeO Adult Seafood 7kg', 480000, 1, 480000),
(2, 17, 'Cát vệ sinh Minino 5L', 85000, 2, 170000),
(2, 9, 'Snack Ciao Churu Chicken 14g x 4', 60000, 3, 180000);

-- Order 3: Đơn hàng mới
INSERT INTO orders (order_number, user_id, customer_name, customer_email, customer_phone, shipping_address, total_amount, status, payment_method, payment_status, created_at)
VALUES
('ORD-20241206-003', 5, 'Phạm Minh Tuấn', 'tuan.pham@gmail.com', '0905678901',
'789 Võ Văn Tần, Quận 3, TP.HCM', 1840000, 'pending', 'COD', 'pending', NOW() - INTERVAL 1 DAY);

INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal)
VALUES
(3, 2, 'Pedigree Adult Grilled Liver 3kg', 399000, 2, 798000),
(3, 6, 'Royal Canin Persian Adult 2kg', 550000, 1, 550000),
(3, 14, 'Vòng cổ dây dắt chó size M', 99000, 1, 99000),
(3, 19, 'Sữa tắm Bio-Groom 473ml', 165000, 1, 165000),
(3, 21, 'Vitamin tổng hợp Virbac', 260000, 1, 260000);

-- Order 4: Đơn hàng đã giao
INSERT INTO orders (order_number, user_id, customer_name, customer_email, customer_phone, shipping_address, total_amount, status, payment_method, payment_status, notes, created_at)
VALUES
('ORD-20241128-004', 6, 'Hoàng Thu Hà', 'ha.hoang@gmail.com', '0906789012',
'321 Hai Bà Trưng, Quận Tân Bình, TP.HCM', 665000, 'delivered', 'Momo', 'paid', 
'Giao buổi chiều sau 3h', NOW() - INTERVAL 10 DAY);

INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal)
VALUES
(4, 16, 'Túi vận chuyển thú cưng size M', 280000, 1, 280000),
(4, 12, 'Bóng chuột nhồi bông cho mèo', 30000, 3, 90000),
(4, 11, 'Cần câu lông vũ cho mèo', 38000, 2, 76000),
(4, 18, 'Khay vệ sinh cho mèo có nắp', 250000, 1, 250000);

-- =====================================================
-- 8. FAQ (Thêm câu hỏi thường gặp)
-- =====================================================

INSERT INTO faqs (question, answer, display_order, status)
VALUES
('Làm thế nào để đặt hàng?', 'Bạn có thể đặt hàng trực tiếp trên website bằng cách thêm sản phẩm vào giỏ hàng và điền thông tin giao hàng. Hoặc liên hệ hotline 1900-xxx để được hỗ trợ đặt hàng.', 4, 'active'),
('Shop có nhận đổi trả hàng không?', 'Có, chúng tôi chấp nhận đổi trả trong vòng 7 ngày nếu sản phẩm còn nguyên vẹn, chưa qua sử dụng và còn hóa đơn mua hàng.', 5, 'active'),
('Tôi có thể thanh toán bằng hình thức nào?', 'Chúng tôi chấp nhận: COD (thanh toán khi nhận hàng), Chuyển khoản ngân hàng, Ví điện tử (Momo, ZaloPay), Thẻ tín dụng/ghi nợ.', 6, 'active'),
('Phí vận chuyển được tính như thế nào?', 'Phí vận chuyển tùy thuộc vào khu vực và trọng lượng đơn hàng. Đơn hàng từ 500.000đ trở lên được miễn phí ship nội thành TP.HCM.', 7, 'active'),
('Sản phẩm có bảo hành không?', 'Thức ăn và tiêu hao phẩm không bảo hành. Các sản phẩm phụ kiện, dụng cụ được bảo hành 3-6 tháng tùy loại theo quy định nhà sản xuất.', 8, 'active')
ON DUPLICATE KEY UPDATE question=question;

-- =====================================================
-- 9. PAGES (Trang nội dung)
-- =====================================================

UPDATE pages SET content_html = 
'<h1>Bảng giá dịch vụ chăm sóc thú cưng</h1>

<h2>Dịch vụ tắm - Spa</h2>
<table border="1" cellpadding="10">
<tr>
<th>Dịch vụ</th>
<th>Chó nhỏ (&lt;5kg)</th>
<th>Chó vừa (5-15kg)</th>
<th>Chó lớn (&gt;15kg)</th>
<th>Mèo</th>
</tr>
<tr>
<td>Tắm cơ bản</td>
<td>100.000đ</td>
<td>150.000đ</td>
<td>200.000đ</td>
<td>120.000đ</td>
</tr>
<tr>
<td>Tắm + Sấy</td>
<td>150.000đ</td>
<td>200.000đ</td>
<td>300.000đ</td>
<td>180.000đ</td>
</tr>
<tr>
<td>Spa cao cấp</td>
<td>250.000đ</td>
<td>350.000đ</td>
<td>500.000đ</td>
<td>280.000đ</td>
</tr>
</table>

<h2>Dịch vụ cắt tỉa lông</h2>
<table border="1" cellpadding="10">
<tr>
<th>Dịch vụ</th>
<th>Giá</th>
</tr>
<tr>
<td>Cắt tỉa lông chó nhỏ</td>
<td>150.000 - 250.000đ</td>
</tr>
<tr>
<td>Cắt tỉa lông chó vừa</td>
<td>250.000 - 400.000đ</td>
</tr>
<tr>
<td>Cắt tỉa lông chó lớn</td>
<td>400.000 - 600.000đ</td>
</tr>
<tr>
<td>Cắt tỉa lông mèo</td>
<td>200.000 - 350.000đ</td>
</tr>
</table>

<h2>Combo tiết kiệm</h2>
<ul>
<li><strong>Combo 1:</strong> Tắm + Vệ sinh tai, mắt, móng = 200.000đ</li>
<li><strong>Combo 2:</strong> Cắt tỉa + Vệ sinh = 350.000đ</li>
<li><strong>Combo 3:</strong> Tắm + Cắt tỉa + Vệ sinh = 400.000đ (Tiết kiệm 15%)</li>
</ul>

<p><em>Lưu ý: Giá có thể thay đổi tùy theo tình trạng lông và kích thước thú cưng.</em></p>'
WHERE slug = 'pricing';

UPDATE pages SET content_html =
'<h1>Dịch vụ chăm sóc thú cưng chuyên nghiệp</h1>

<h2>1. Dịch vụ Tắm - Spa</h2>
<p>Chúng tôi cung cấp dịch vụ tắm và spa chuyên nghiệp với:</p>
<ul>
<li>Sữa tắm cao cấp phù hợp từng loại da lông</li>
<li>Massage thư giãn cho thú cưng</li>
<li>Vệ sinh tai, mắt, móng chuyên nghiệp</li>
<li>Sấy khô an toàn với thiết bị hiện đại</li>
</ul>

<h2>2. Cắt tỉa lông chuyên nghiệp</h2>
<p>Đội ngũ groomer có kinh nghiệm trên 5 năm:</p>
<ul>
<li>Cắt tỉa theo yêu cầu hoặc chuẩn giống</li>
<li>Tư vấn kiểu tỉa phù hợp</li>
<li>Sử dụng dụng cụ chuyên dụng an toàn</li>
</ul>

<h2>3. Khám sức khỏe định kỳ</h2>
<p>Hợp tác với bác sĩ thú y uy tín:</p>
<ul>
<li>Khám tổng quát định kỳ</li>
<li>Tiêm phòng đầy đủ</li>
<li>Tẩy giun, ve rận</li>
<li>Tư vấn dinh dưỡng</li>
</ul>

<h2>4. Khách sạn thú cưng</h2>
<p>Dịch vụ lưu trú an toàn cho thú cưng khi bạn đi xa:</p>
<ul>
<li>Phòng ốc sạch sẽ, thoáng mát</li>
<li>Chăm sóc 24/7</li>
<li>Cho ăn đúng giờ, đúng khẩu phần</li>
<li>Camera theo dõi trực tuyến</li>
</ul>

<h2>Liên hệ đặt lịch</h2>
<p>Hotline: <strong>1900-xxxx</strong></p>
<p>Email: <strong>service@petcare.com</strong></p>
<p>Địa chỉ: <strong>123 Nguyễn Văn Cừ, Q.5, TP.HCM</strong></p>'
WHERE slug = 'service';

-- =====================================================
-- HOÀN TẤT
-- =====================================================

SELECT 'Sample data has been inserted successfully!' as message;
SELECT 
    (SELECT COUNT(*) FROM users) as users,
    (SELECT COUNT(*) FROM categories) as categories,
    (SELECT COUNT(*) FROM products) as products,
    (SELECT COUNT(*) FROM posts) as posts,
    (SELECT COUNT(*) FROM post_comments) as comments,
    (SELECT COUNT(*) FROM orders) as orders,
    (SELECT COUNT(*) FROM contacts) as contacts,
    (SELECT COUNT(*) FROM faqs) as faqs;
