<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $this->view('auth.login', [
            'title' => 'Đăng nhập',
        ], 'Đăng nhập - ' . APP_NAME, 'auth');
    }

    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        if (Auth::check()) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng nhập email và mật khẩu';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $user = User::findByEmail($email);
        // if (!$user || !User::verifyPassword($password, $user['password'])) {
        //     $_SESSION['error'] = 'Email hoặc mật khẩu không đúng';
        //     header('Location: ' . BASE_URL . 'login');
        //     exit;
        // }

        // SỬA: So sánh trực tiếp biến $password nhập vào và $user['password'] trong database
        if (!$user || $password !== $user['password']) {
            $_SESSION['flash_error'] = 'Email hoặc mật khẩu không đúng'; // Nên dùng flash_error cho đồng bộ
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $_SESSION['user'] = $user;
        $_SESSION['success'] = 'Đăng nhập thành công';

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: ' . BASE_URL . 'admin/products');
        } else {
            header('Location: ' . BASE_URL);
        }
        exit;
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
}
