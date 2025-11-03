<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) { header('Location: '.BASE_URL); return; }
        $this->view('auth.login', [ 'title' => 'Đăng nhập' ]);
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrf = $_POST['_csrf'] ?? '';
        if (!Auth::checkCsrf($csrf)) { http_response_code(400); echo 'CSRF invalid'; return; }
        if (!$email || !$password) {
            $this->view('auth.login', ['title'=>'Đăng nhập','error'=>'Vui lòng nhập email và mật khẩu']);
            return;
        }
        if (!Auth::attempt($email, $password)) {
            $this->view('auth.login', ['title'=>'Đăng nhập','error'=>'Thông tin đăng nhập không đúng hoặc tài khoản bị khoá']);
            return;
        }
        header('Location: ' . BASE_URL);
    }

    public function showRegister(): void
    {
        if (Auth::check()) { header('Location: '.BASE_URL); return; }
        $this->view('auth.register', [ 'title' => 'Đăng ký' ]);
    }

    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $csrf = $_POST['_csrf'] ?? '';
        if (!Auth::checkCsrf($csrf)) { http_response_code(400); echo 'CSRF invalid'; return; }
        if (!$name || !$email || !$password || !$password2) {
            $this->view('auth.register', ['title'=>'Đăng ký','error'=>'Vui lòng nhập đủ thông tin']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth.register', ['title'=>'Đăng ký','error'=>'Email không hợp lệ']);
            return;
        }
        if ($password !== $password2 || strlen($password) < 6) {
            $this->view('auth.register', ['title'=>'Đăng ký','error'=>'Mật khẩu không khớp hoặc quá ngắn (>=6 ký tự)']);
            return;
        }
        if (User::findByEmail($email)) {
            $this->view('auth.register', ['title'=>'Đăng ký','error'=>'Email đã được sử dụng']);
            return;
        }
        $uid = User::create($name, $email, $password, $phone ?: null, 'customer');
        // Auto login
        if (Auth::attempt($email, $password)) {
            header('Location: ' . BASE_URL);
        } else {
            $this->view('auth.login', ['title'=>'Đăng nhập','error'=>'Đăng ký thành công, vui lòng đăng nhập']);
        }
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . BASE_URL);
    }
}
