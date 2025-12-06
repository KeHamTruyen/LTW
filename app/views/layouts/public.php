<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>assets/css/public.css" rel="stylesheet">
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="header-top">
            <div class="header-contact">
                <div class="contact-item">
                    <svg class="icon-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.9975 16.92V19.92C21.9986 20.1985 21.9416 20.4741 21.83 20.7293C21.7184 20.9845 21.5548 21.2136 21.3496 21.4018C21.1443 21.5901 20.9021 21.7335 20.6382 21.8227C20.3744 21.9119 20.0949 21.945 19.8175 21.92C16.7403 21.5856 13.7845 20.5341 11.1875 18.85C8.77132 17.3146 6.72283 15.2661 5.18749 12.85C3.49747 10.2412 2.44573 7.27097 2.11749 4.17997C2.0925 3.90344 2.12537 3.62474 2.21399 3.3616C2.30262 3.09846 2.44506 2.85666 2.63226 2.6516C2.81945 2.44653 3.0473 2.28268 3.30128 2.1705C3.55527 2.05831 3.82983 2.00024 4.10749 1.99997H7.10749C7.5928 1.9952 8.06328 2.16705 8.43125 2.48351C8.79922 2.79996 9.03957 3.23942 9.10749 3.71997C9.23411 4.68004 9.46894 5.6227 9.80749 6.52997C9.94204 6.8879 9.97115 7.27689 9.8914 7.65086C9.81164 8.02482 9.62635 8.36809 9.35749 8.63998L8.08749 9.90997C9.51105 12.4135 11.5839 14.4864 14.0875 15.91L15.3575 14.64C15.6294 14.3711 15.9726 14.1858 16.3466 14.1061C16.7206 14.0263 17.1096 14.0554 17.4675 14.19C18.3748 14.5285 19.3174 14.7634 20.2775 14.89C20.7633 14.9585 21.2069 15.2032 21.524 15.5775C21.8412 15.9518 22.0097 16.4296 21.9975 16.92Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>+039 871-5611</span>
                </div>
                <div class="contact-item">
                    <svg class="icon-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 6L12 13L2 6" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>petschoice@outlook.com</span>
                </div>
            </div>

            <div class="header-actions">
                <div class="search-box">
                    <input type="text" placeholder="Search ...">
                    <button class="search-btn">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.36892 14.4449C12.1745 14.4449 14.4488 12.1705 14.4488 9.36502C14.4488 6.55949 12.1745 4.28516 9.36892 4.28516C6.56339 4.28516 4.28906 6.55949 4.28906 9.36502C4.28906 12.1705 6.56339 14.4449 9.36892 14.4449Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15.7153 15.7149L12.9531 12.9527" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>

                <a href="<?= BASE_URL ?>cart" class="cart-icon" style="position: relative; display: inline-block; cursor: pointer;">
                    <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 29C9.55228 29 10 28.5523 10 28C10 27.4477 9.55228 27 9 27C8.44772 27 8 27.4477 8 28C8 28.5523 8.44772 29 9 29Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 29C20.5523 29 21 28.5523 21 28C21 27.4477 20.5523 27 20 27C19.4477 27 19 27.4477 19 28C19 28.5523 19.4477 29 20 29Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1 8H5L7.68 21.39C7.77144 21.8504 8.02191 22.264 8.38755 22.5583C8.75318 22.8526 9.2107 23.009 9.68 23H19.4C19.8693 23.009 20.3268 22.8526 20.6925 22.5583C21.0581 22.264 21.3086 21.8504 21.4 21.39L23 13H6" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="cart-badge">0</div>
                </a>

                <?php
                use Core\Auth;
                $isLoggedIn = Auth::check();
                $user = Auth::user();
                $avatarUrl = $_SESSION['user_avatar'] ?? null;
                $userName = $_SESSION['user_name'] ?? ($user['name'] ?? '');
                ?>
                
                <?php if ($isLoggedIn): ?>
                    <!-- User Avatar with Dropdown -->
                    <div class="user-avatar-container" style="position: relative; display: inline-block; margin-left: 10px;">
                        <button id="userAvatarBtn" class="user-avatar-link" style="display: inline-block; width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 2px solid #000; cursor: pointer; background: #f0f0f0; transition: transform 0.2s; padding: 0;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <?php if (!empty($avatarUrl)): ?>
                                <img src="<?= htmlspecialchars($avatarUrl) ?>" 
                                     alt="<?= htmlspecialchars($userName) ?>" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #206bc4; color: white; font-weight: bold; font-size: 16px;">
                                    <?= strtoupper(mb_substr($userName, 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </button>
                        <div id="userDropdown" class="user-dropdown" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-width: 180px; z-index: 1000;">
                            <div style="padding: 12px; border-bottom: 1px solid #eee;">
                                <div style="font-weight: 600; color: #333;"><?= htmlspecialchars($userName) ?></div>
                                <div style="font-size: 12px; color: #666; margin-top: 2px;"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></div>
                            </div>
                            <div style="padding: 4px 0;">
                                <a href="<?= BASE_URL ?>profile" style="display: block; padding: 10px 16px; color: #333; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='white'">
                                    👤 Thông tin cá nhân
                                </a>
                                <?php if (Auth::isAdmin()): ?>
                                    <a href="<?= BASE_URL ?>admin" style="display: block; padding: 10px 16px; color: #333; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='white'">
                                    ⚙️ Quản lý trang web
                                </a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>logout" style="display: block; padding: 10px 16px; color: #d32f2f; text-decoration: none; transition: background 0.2s; border-top: 1px solid #eee; margin-top: 4px;" onmouseover="this.style.background='#ffebee'" onmouseout="this.style.background='white'">
                                    🚪 Đăng xuất
                                </a>
                            </div>
                        </div>
                    </div>
                    <script>
                    // Toggle dropdown
                    document.getElementById('userAvatarBtn')?.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const dropdown = document.getElementById('userDropdown');
                        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
                    });
                    
                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(e) {
                        const container = document.querySelector('.user-avatar-container');
                        const dropdown = document.getElementById('userDropdown');
                        if (container && !container.contains(e.target)) {
                            dropdown.style.display = 'none';
                        }
                    });
                    </script>
                <?php else: ?>
                    <!-- Login/Register Buttons -->
                    <div class="auth-buttons">
                        <button class="auth-btn" onclick="window.location='<?= BASE_URL ?>login'">Đăng nhập</button>
                        <button class="auth-btn" onclick="window.location='<?= BASE_URL ?>register'">Đăng Ký</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- NAVIGATION -->
    <div class="nav-container">
        <nav class="nav">
            <a href="<?= BASE_URL ?>" class="logo">
                <img src="<?= htmlspecialchars($homeData['company_logo_url'] ?? BASE_URL . 'assets/images/logo.png') ?>" alt="Pet's Choice Logo" onerror="this.src='https://via.placeholder.com/89x76'">
            </a>

            <div class="nav-menu">
                <a href="<?= BASE_URL ?>" class="nav-link <?= isset($activeMenu) && $activeMenu === 'home' ? 'active' : '' ?>">Home</a>
                <a href="<?= BASE_URL ?>about" class="nav-link <?= isset($activeMenu) && $activeMenu === 'about' ? 'active' : '' ?>">About Us</a>
                <a href="<?= BASE_URL ?>faq" class="nav-link <?= isset($activeMenu) && $activeMenu === 'faq' ? 'active' : '' ?>">Q&A</a>
                <a href="<?= BASE_URL ?>shop" class="nav-link <?= isset($activeMenu) && $activeMenu === 'shop' ? 'active' : '' ?>">Shop</a>
                <a href="<?= BASE_URL ?>posts" class="nav-link <?= isset($activeMenu) && $activeMenu === 'blog' ? 'active' : '' ?>">Blog</a>
                <a href="<?= BASE_URL ?>contact" class="nav-link <?= isset($activeMenu) && $activeMenu === 'contact' ? 'active' : '' ?>">Contact Us</a>
            </div>
        </nav>
    </div>

    <!-- HERO WITH 4 DOGS - Only show on blog listing page, not on blog detail -->
    <?php if (!isset($hideBlogHero) || !$hideBlogHero): ?>
    <section class="hero-dogs">
        <div class="hero-dogs-wrapper">
            <div class="hero-image-container">
                <img src="<?= BASE_URL ?>assets/images/4dogs.png" alt="Pet animals" class="dogs-image">
            </div>
            
            <div class="hero-title-box">
                <h1 class="hero-title">Khám phá bài viết</h1>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CONTENT -->
    <main>
        <?php $content(); ?>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-grid">
                <div class="footer-logo-section">
                    <img src="<?= htmlspecialchars($homeData['company_logo_url'] ?? BASE_URL . 'assets/images/logo.png') ?>" alt="Pet's Choice Logo" onerror="this.src='https://via.placeholder.com/89x76'">
                    <p class="footer-description">Chăm sóc thú cưng tận tâm, dịch vụ chuyên nghiệp, giá cả hợp lý. Đồng hành cùng bạn chăm sóc thú cưng yêu quý.</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.9921 12.0004C23.9921 18.0612 19.5008 23.0717 13.6663 23.8848C13.1208 23.9605 12.5626 24 11.996 24C11.3421 24 10.6999 23.9479 10.0745 23.847C4.36266 22.9271 0 17.9729 0 12.0004C0 5.37294 5.37136 0 11.9969 0C18.6224 0 23.9938 5.37294 23.9938 12.0004H23.9921Z" fill="black"/>
                                <path d="M13.6662 9.63588V12.25H16.8991L16.3872 15.7715H13.6662V23.8847C13.1206 23.9604 12.5625 23.9999 11.9959 23.9999C11.342 23.9999 10.6997 23.9478 10.0743 23.8469V15.7715H7.09277V12.25H10.0743V9.0515C10.0743 7.06713 11.6824 5.45776 13.667 5.45776V5.45945C13.6729 5.45945 13.6779 5.45776 13.6838 5.45776H16.8999V8.50328H14.7985C14.1739 8.50328 13.667 9.0103 13.667 9.63504L13.6662 9.63588Z" fill="white"/>
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="12" fill="black"/>
                                <path d="M15.7023 4.88257H8.2782C6.2272 4.88257 4.5586 6.55163 4.5586 8.60327V15.3972C4.5586 17.4489 6.2272 19.1179 8.2782 19.1179H15.7023C17.7533 19.1179 19.4219 17.4489 19.4219 15.3972V8.60327C19.4219 6.55163 17.7533 4.88257 15.7023 4.88257Z" stroke="white" stroke-width="1.3"/>
                                <circle cx="12" cy="12" r="3.5" stroke="white" stroke-width="1.3"/>
                                <circle cx="15.77" cy="8.17" r="0.93" fill="white"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="footer-section">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="<?= BASE_URL ?>about">About Us</a></li>
                        <li><a href="<?= BASE_URL ?>posts">Blog</a></li>
                        <li><a href="<?= BASE_URL ?>careers">Careers</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Useful Links</h3>
                    <ul>
                        <li><a href="<?= BASE_URL ?>shop?filter=new">New products</a></li>
                        <li><a href="<?= BASE_URL ?>shop?filter=bestsellers">Best sellers</a></li>
                        <li><a href="<?= BASE_URL ?>shop?filter=discount">Discount</a></li>
                        <li><a href="<?= BASE_URL ?>faq">F.A.Q</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="<?= BASE_URL ?>contact">Contact Us</a></li>
                        <li><a href="<?= BASE_URL ?>shipping">Shipping</a></li>
                        <li><a href="<?= BASE_URL ?>returns">Returns</a></li>
                        <li><a href="<?= BASE_URL ?>tracking">Order tracking</a></li>
                    </ul>
                </div>

                <div class="footer-store">
                    <h3>Store</h3>
                    <div class="store-address">
                        Khu phố 6, Phường Linh Trung<br>
                        Thủ Đức, TP.HCM
                    </div>
                    <div class="store-contact">
                        <div class="store-contact-item">+039 871-5611</div>
                        <div class="store-contact-item">petschoice@outlook.com</div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="copyright">© <?= date('Y') ?> Pet's Choice. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
