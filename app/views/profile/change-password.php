<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Đổi mật khẩu</h1>
        
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
            <form method="post" action="<?= BASE_URL ?>profile/update-password" id="passwordForm">
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                
                <!-- Current Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Mật khẩu hiện tại <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="current_password" 
                           id="current_password"
                           required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- New Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Mật khẩu mới <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="new_password" 
                           id="new_password"
                           required 
                           minlength="6"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Tối thiểu 6 ký tự</p>
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Xác nhận mật khẩu mới <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="confirm_password" 
                           id="confirm_password"
                           required 
                           minlength="6"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-red-500" id="passwordMatch" style="display:none;">Mật khẩu không khớp</p>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Đổi mật khẩu
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
// Password match validation
const newPassword = document.getElementById('new_password');
const confirmPassword = document.getElementById('confirm_password');
const passwordMatch = document.getElementById('passwordMatch');

function checkPasswordMatch() {
    if (confirmPassword.value && newPassword.value !== confirmPassword.value) {
        passwordMatch.style.display = 'block';
        confirmPassword.setCustomValidity('Mật khẩu không khớp');
    } else {
        passwordMatch.style.display = 'none';
        confirmPassword.setCustomValidity('');
    }
}

newPassword.addEventListener('input', checkPasswordMatch);
confirmPassword.addEventListener('input', checkPasswordMatch);

// Form validation
document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
    const currentPassword = document.getElementById('current_password').value;
    const newPasswordValue = newPassword.value;
    const confirmPasswordValue = confirmPassword.value;
    
    if (newPasswordValue.length < 6) {
        e.preventDefault();
        alert('Mật khẩu mới phải có ít nhất 6 ký tự');
        return false;
    }
    
    if (newPasswordValue !== confirmPasswordValue) {
        e.preventDefault();
        alert('Mật khẩu xác nhận không khớp');
        return false;
    }
});
</script>

