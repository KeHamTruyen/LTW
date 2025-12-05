<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(): void
    {
        // If already logged in, redirect to appropriate page
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                header('Location: ' . BASE_URL . 'admin');
            } else {
                header('Location: ' . BASE_URL);
            }
            exit;
        }

        $this->view('auth.login', [
            'title' => 'Đăng nhập',
            '_layout' => 'auth',
        ]);
    }

    public function doLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $remember = isset($_POST['remember']) ? (bool)$_POST['remember'] : false;

        // Validate input
        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'Vui lòng nhập đầy đủ thông tin';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Find user by email
        $user = User::findByEmail($email);

        // Verify password
        if (!$user || !password_verify($password, $user['password_hash']) || $user['status'] !== 'active') {
            $_SESSION['flash_error'] = 'Email hoặc mật khẩu không đúng';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        // Update last login
        User::updateLastLogin($user['id']);

        // Set remember me cookie (30 days)
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
        }

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: ' . BASE_URL . 'admin');
        } else {
            header('Location: ' . BASE_URL);
        }
        exit;
    }

    public function register(): void
    {
        // If already logged in, redirect
        if (Auth::check()) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $this->view('auth.register', [
            'title' => 'Đăng ký',
            '_layout' => 'auth',
        ]);
    }

    public function doRegister(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $passwordConfirm = isset($_POST['password_confirm']) ? trim($_POST['password_confirm']) : '';

        // Validate input
        $errors = [];
        if (empty($name) || mb_strlen($name) < 2) {
            $errors[] = 'Tên phải có ít nhất 2 ký tự';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }
        if (empty($password) || mb_strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        if ($password !== $passwordConfirm) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        // Check if email already exists
        if (User::findByEmail($email)) {
            $_SESSION['flash_error'] = 'Email đã được sử dụng';
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        // Create user
        try {
            $userId = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'customer',
                'status' => 'active',
            ]);

            // Auto login after registration
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'customer';

            $_SESSION['flash_success'] = 'Đăng ký thành công!';
            header('Location: ' . BASE_URL);
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.';
            error_log('Register error: ' . $e->getMessage());
            header('Location: ' . BASE_URL . 'register');
            exit;
        }
    }

    public function logout(): void
    {
        // Clear session
        session_destroy();
        
        // Clear remember me cookie
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        
        // Redirect to home
        header('Location: ' . BASE_URL);
        exit;
    }
}
