<?php
$user = $user ?? null;
if (!$user) {
    echo '<div class="container mx-auto px-4 py-8"><p>Không tìm thấy thông tin người dùng.</p></div>';
    return;
}
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Thông tin cá nhân</h1>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Hồ sơ của tôi</h2>
                    <a href="<?= BASE_URL ?>profile/edit" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Chỉnh sửa
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                            <?php if (!empty($user['avatar_url'])): ?>
                                <img src="<?= htmlspecialchars($user['avatar_url']) ?>" 
                                     alt="Avatar" 
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex-1 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Họ và tên</label>
                            <p class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($user['name']) ?></p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-lg text-gray-800"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                        
                        <?php if (!empty($user['phone'])): ?>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Số điện thoại</label>
                                <p class="text-lg text-gray-800"><?= htmlspecialchars($user['phone']) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">Vai trò</label>
                            <p class="text-lg">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold <?php
                                    switch ($user['role']) {
                                        case 'admin':
                                            echo 'bg-purple-100 text-purple-800';
                                            break;
                                        case 'staff':
                                            echo 'bg-blue-100 text-blue-800';
                                            break;
                                        default:
                                            echo 'bg-gray-100 text-gray-800';
                                    }
                                ?>">
                                    <?php
                                    switch ($user['role']) {
                                        case 'admin':
                                            echo 'Quản trị viên';
                                            break;
                                        case 'staff':
                                            echo 'Nhân viên';
                                            break;
                                        default:
                                            echo 'Khách hàng';
                                    }
                                    ?>
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">Ngày tham gia</label>
                            <p class="text-lg text-gray-800">
                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                            </p>
                        </div>
                        
                        <?php if (!empty($user['last_login'])): ?>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Lần đăng nhập cuối</label>
                                <p class="text-lg text-gray-800">
                                    <?= date('d/m/Y H:i', strtotime($user['last_login'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="p-6 bg-gray-50 border-t">
                <div class="flex gap-4">
                    <a href="<?= BASE_URL ?>profile/edit" 
                       class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Chỉnh sửa thông tin
                    </a>
                    <a href="<?= BASE_URL ?>profile/change-password" 
                       class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-white transition">
                        Đổi mật khẩu
                    </a>
                    <a href="<?= BASE_URL ?>orders" 
                       class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-white transition">
                        Đơn hàng của tôi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

