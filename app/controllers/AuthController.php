<?php
namespace App\Controllers;

use Core\Controller;
use Core\Database;

class AuthController extends Controller
{
    public function login(): void
    {
        // If already logged in, redirect to appropriate page
        if (isset($_SESSION['user_id'])) {
            if (($_SESSION['user_role'] ?? '') === 'admin') {
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
        $remember = isset($_POST['remember']);

        // Validate input
        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'Vui lòng nhập đầy đủ thông tin';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Find user by email
        $pdo = Database::conn();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Verify password
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Email hoặc mật khẩu không đúng';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        // Set remember me cookie (30 days)
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
            
            // Store token in database (you should create a remember_tokens table)
            // For now, we'll skip this and just set the cookie
        }

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: ' . BASE_URL . 'admin');
        } else {
            header('Location: ' . BASE_URL);
        }
        exit;
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
