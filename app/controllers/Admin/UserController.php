<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();
        $q = trim($_GET['q'] ?? '') ?: null;
        $users = User::listUsers($q);
        $this->view('admin.users.index', ['title'=>'Quản lý người dùng','users'=>$users,'q'=>$q]);
    }

    public function setStatus(): void
    {
        Auth::requireAdmin();
        if (!Auth::checkCsrf($_POST['_csrf'] ?? '')) { http_response_code(400); echo 'CSRF invalid'; return; }
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'active';
        if (!in_array($status, ['active','inactive','banned'], true)) $status = 'inactive';
        if ($id > 0) User::setStatus($id, $status);
        header('Location: ' . BASE_URL . 'admin/users');
    }

    public function setRole(): void
    {
        Auth::requireAdmin();
        if (!Auth::checkCsrf($_POST['_csrf'] ?? '')) { http_response_code(400); echo 'CSRF invalid'; return; }
        $id = (int)($_POST['id'] ?? 0);
        $role = $_POST['role'] ?? 'customer';
        if (!in_array($role, ['customer','staff','admin'], true)) $role = 'customer';
        if ($id > 0) User::setRole($id, $role);
        header('Location: ' . BASE_URL . 'admin/users');
    }

    public function resetPassword(): void
    {
        Auth::requireAdmin();
        if (!Auth::checkCsrf($_POST['_csrf'] ?? '')) { http_response_code(400); echo 'CSRF invalid'; return; }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $newPass = User::resetPassword($id);
            // For demo: display new password; in production send via email.
            $_SESSION['flash'] = 'Mật khẩu mới cho user #' . $id . ': ' . $newPass;
        }
        header('Location: ' . BASE_URL . 'admin/users');
    }
}
