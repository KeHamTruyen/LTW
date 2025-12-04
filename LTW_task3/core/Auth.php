<?php
namespace Core;

class Auth
{
    // Lấy thông tin user từ Session
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    // Kiểm tra đã đăng nhập chưa
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    // Lấy ID người dùng hiện tại
    public static function id(): ?int
    {
        // Lưu ý: Database trả về 'ID' (viết hoa) nên key ở đây phải là 'ID'
        return self::user()['ID'] ?? null;
    }

    // Kiểm tra quyền Admin
    public static function isAdmin(): bool
    {
        // Database trả về 'role' (viết thường)
        return (self::user()['role'] ?? null) === 'admin';
    }

    // Yêu cầu đăng nhập (Middleware)
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    // Yêu cầu quyền Admin (Middleware)
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            http_response_code(403);
            echo 'Forbidden - Bạn không có quyền truy cập trang này';
            exit;
        }
    }

    // Tạo CSRF Token
    public static function csrfToken(): string
    {
        // SỬA: Đổi '_csrf' thành 'csrf' để khớp với Controller
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    // Kiểm tra CSRF Token
    public static function checkCsrf(?string $token): bool
    {
        // SỬA: Đổi '_csrf' thành 'csrf'
        return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) $token);
    }
}