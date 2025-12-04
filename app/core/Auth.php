<?php
namespace Core;

use Core\Database;

class Auth
{
    public static function user(): ?array 
    { 
        // Support both session formats for backward compatibility
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        
        // Build user array from individual session variables
        if (isset($_SESSION['user_id'])) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['user_role'] ?? 'user',
            ];
        }
        
        return null;
    }
    
    public static function check(): bool 
    { 
        return isset($_SESSION['user']) || isset($_SESSION['user_id']); 
    }
    
    public static function id(): ?int 
    { 
        if (isset($_SESSION['user_id'])) {
            return (int)$_SESSION['user_id'];
        }
        return self::user()['id'] ?? null; 
    }
    
    public static function isAdmin(): bool 
    { 
        if (isset($_SESSION['user_role'])) {
            return $_SESSION['user_role'] === 'admin';
        }
        return (self::user()['role'] ?? null) === 'admin'; 
    }

    public static function requireLogin(): void
    {
        if (!self::check()) { 
            header('Location: ' . BASE_URL . 'login'); 
            exit; 
        }
    }
    
    public static function requireAdmin(): void
    {
        if (!self::check()) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        if (!self::isAdmin()) { 
            http_response_code(403); 
            echo 'Forbidden - Admin access required'; 
            exit; 
        }
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf'])) { 
            $_SESSION['_csrf'] = bin2hex(random_bytes(32)); 
        }
        return $_SESSION['_csrf'];
    }
    
    public static function checkCsrf(?string $token): bool
    { 
        return isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], (string)$token); 
    }
}
