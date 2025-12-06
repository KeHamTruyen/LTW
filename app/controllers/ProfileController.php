<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\User;
use App\Models\Home;

class ProfileController extends Controller
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    public function index(): void
    {
        $user = User::findById(Auth::id());
        if (!$user) {
            http_response_code(404);
            echo '404 - Người dùng không tồn tại';
            exit;
        }

        $homeData = Home::get();
        
        $this->view('profile.index', [
            'title' => 'Thông tin cá nhân - ' . APP_NAME,
            'user' => $user,
            'activeMenu' => 'profile',
            'hideBlogHero' => true,
            'homeData' => $homeData,
        ], null, 'public');
    }

    public function edit(): void
    {
        $user = User::findById(Auth::id());
        if (!$user) {
            http_response_code(404);
            echo '404 - Người dùng không tồn tại';
            exit;
        }

        $homeData = Home::get();
        
        $this->view('profile.edit', [
            'title' => 'Chỉnh sửa thông tin - ' . APP_NAME,
            'user' => $user,
            'activeMenu' => 'profile',
            'hideBlogHero' => true,
            'homeData' => $homeData,
        ], null, 'public');
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'profile/edit');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'profile/edit');
            exit;
        }

        $userId = Auth::id();
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

        // Validate
        $errors = [];
        if (empty($name) || mb_strlen($name) < 2) {
            $errors[] = 'Tên phải có ít nhất 2 ký tự';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . 'profile/edit');
            exit;
        }

        // Check if email is taken by another user
        $existingUser = User::findByEmail($email);
        if ($existingUser && $existingUser['id'] != $userId) {
            $_SESSION['flash_error'] = 'Email đã được sử dụng bởi tài khoản khác';
            header('Location: ' . BASE_URL . 'profile/edit');
            exit;
        }

        // Handle avatar upload
        $avatarUrl = null;
        if (!empty($_FILES['avatar']['name'])) {
            $file = $_FILES['avatar'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            if (in_array($ext, $allowed) && $file['size'] <= 2 * 1024 * 1024) {
                $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename = uniqid('avatar_') . '.' . $ext;
                $filepath = $uploadDir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $avatarUrl = BASE_URL . 'uploads/avatars/' . $filename;
                }
            }
        }

        // Update user
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ];
        
        if ($avatarUrl) {
            $data['avatar_url'] = $avatarUrl;
        }

        if (User::update($userId, $data)) {
            // Update session
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            if ($avatarUrl) {
                $_SESSION['user_avatar'] = $avatarUrl;
            }
            
            $_SESSION['flash_success'] = 'Cập nhật thông tin thành công!';
            header('Location: ' . BASE_URL . 'profile');
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật thông tin';
            header('Location: ' . BASE_URL . 'profile/edit');
        }
        exit;
    }

    public function changePassword(): void
    {
        $homeData = Home::get();
        
        $this->view('profile.change-password', [
            'title' => 'Đổi mật khẩu - ' . APP_NAME,
            'activeMenu' => 'profile',
            'hideBlogHero' => true,
            'homeData' => $homeData,
        ], null, 'public');
    }

    public function updatePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'profile/change-password');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'profile/change-password');
            exit;
        }

        $userId = Auth::id();
        $currentPassword = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
        $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
        $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

        // Validate
        $errors = [];
        if (empty($currentPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu hiện tại';
        }
        if (empty($newPassword) || mb_strlen($newPassword) < 6) {
            $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . 'profile/change-password');
            exit;
        }

        // Verify current password
        $user = User::findById($userId);
        if (!password_verify($currentPassword, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Mật khẩu hiện tại không đúng';
            header('Location: ' . BASE_URL . 'profile/change-password');
            exit;
        }

        // Update password
        if (User::updatePassword($userId, password_hash($newPassword, PASSWORD_DEFAULT))) {
            $_SESSION['flash_success'] = 'Đổi mật khẩu thành công!';
            header('Location: ' . BASE_URL . 'profile');
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi đổi mật khẩu';
            header('Location: ' . BASE_URL . 'profile/change-password');
        }
        exit;
    }
}

