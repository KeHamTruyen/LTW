<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(): void
    {
        Auth::requireLogin();
        $user = User::findById(Auth::id());
        $this->view('profile.edit', [ 'title' => 'Hồ sơ cá nhân', 'user' => $user ]);
    }

    public function update(): void
    {
        Auth::requireLogin();
        if (!Auth::checkCsrf($_POST['_csrf'] ?? '')) { http_response_code(400); echo 'CSRF invalid'; return; }
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $avatarUrl = null;

        // Handle avatar upload
        if (!empty($_FILES['avatar']['name'])) {
            $avatarUrl = $this->handleAvatarUpload($_FILES['avatar']);
            if ($avatarUrl === null) {
                $user = User::findById(Auth::id());
                $this->view('profile.edit', ['title'=>'Hồ sơ cá nhân','user'=>$user,'error'=>'Ảnh không hợp lệ (jpg/png, <= 2MB)']);
                return;
            }
        } else {
            $current = User::findById(Auth::id());
            $avatarUrl = $current['avatar_url'] ?? null;
        }

        if (!$name) {
            $user = User::findById(Auth::id());
            $this->view('profile.edit', ['title'=>'Hồ sơ cá nhân','user'=>$user,'error'=>'Tên không được để trống']);
            return;
        }

        User::updateProfile(Auth::id(), $name, $phone ?: null, $avatarUrl);
        // refresh session data
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone ?: null;
        $_SESSION['user']['avatar_url'] = $avatarUrl;
        header('Location: ' . BASE_URL . 'profile');
    }

    public function updatePassword(): void
    {
        Auth::requireLogin();
        if (!Auth::checkCsrf($_POST['_csrf'] ?? '')) { http_response_code(400); echo 'CSRF invalid'; return; }
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $new2 = $_POST['new_password2'] ?? '';

        $user = User::findById(Auth::id());
        if (!$user || !password_verify($current, $user['password_hash'])) {
            $user = User::findById(Auth::id());
            $this->view('profile.edit', ['title'=>'Hồ sơ cá nhân','user'=>$user,'tab'=>'password','error'=>'Mật khẩu hiện tại không đúng']);
            return;
        }
        if ($new !== $new2 || strlen($new) < 6) {
            $user = User::findById(Auth::id());
            $this->view('profile.edit', ['title'=>'Hồ sơ cá nhân','user'=>$user,'tab'=>'password','error'=>'Mật khẩu mới không khớp hoặc quá ngắn (>=6)']);
            return;
        }
        User::updatePassword(Auth::id(), $new);
        header('Location: ' . BASE_URL . 'profile');
    }

    private function handleAvatarUpload(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;
        if ($file['size'] > 2*1024*1024) return null; // 2MB
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $ext = match($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            default => null,
        };
        if (!$ext) return null;
        $base = bin2hex(random_bytes(8));
        $filename = $base . '.' . $ext;
        $destDir = __DIR__ . '/../../public/uploads/avatars/';
        $publicPath = 'uploads/avatars/' . $filename;
        if (!is_dir($destDir)) { @mkdir($destDir, 0777, true); }
        $dest = $destDir . $filename;
        if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
        return $publicPath; // relative to public
    }
}
