<?php
$user = $user ?? null;
if (!$user) {
    echo '<div class="container mx-auto px-4 py-8"><p>Không tìm thấy thông tin người dùng.</p></div>';
    return;
}
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Chỉnh sửa thông tin</h1>
        
        <!-- Flash messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="post" action="<?= BASE_URL ?>profile/update" enctype="multipart/form-data" id="profileForm">
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                
                <!-- Avatar -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh đại diện</label>
                    <div class="flex items-center gap-4">
                        <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                            <?php if (!empty($user['avatar_url'])): ?>
                                <img src="<?= htmlspecialchars($user['avatar_url']) ?>" 
                                     alt="Avatar" 
                                     id="avatarPreview"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <input type="file" 
                                   name="avatar" 
                                   id="avatarInput"
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF tối đa 2MB</p>
                        </div>
                    </div>
                </div>
                
                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Họ và tên <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="<?= htmlspecialchars($user['name']) ?>"
                           required 
                           minlength="2"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="<?= htmlspecialchars($user['email']) ?>"
                           required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Phone -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Số điện thoại
                    </label>
                    <input type="tel" 
                           name="phone" 
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Cập nhật
                    </button>
                    <a href="<?= BASE_URL ?>profile" 
                       class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-center">
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Avatar preview
document.getElementById('avatarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            if (preview) {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.id = 'avatarPreview';
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                const container = document.querySelector('.w-24.h-24');
                if (container) {
                    container.innerHTML = '';
                    container.appendChild(img);
                }
            }
        };
        reader.readAsDataURL(file);
    }
});

// Form validation
document.getElementById('profileForm')?.addEventListener('submit', function(e) {
    const name = this.querySelector('[name="name"]').value.trim();
    const email = this.querySelector('[name="email"]').value.trim();
    
    if (name.length < 2) {
        e.preventDefault();
        alert('Họ và tên phải có ít nhất 2 ký tự');
        return false;
    }
    
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        e.preventDefault();
        alert('Email không hợp lệ');
        return false;
    }
});
</script>

