<?php
namespace Core;

use Core\Database;

class Auth
{
    public static function user(): ?array { return $_SESSION['user'] ?? null; }
    public static function check(): bool { return isset($_SESSION['user']); }
    public static function id(): ?int { return self::user()['id'] ?? null; }
    public static function isAdmin(): bool { return (self::user()['role'] ?? null) === 'admin'; }

    public static function requireLogin(): void
    {
        if (!self::check()) { header('Location: ' . BASE_URL . 'login'); exit; }
    }
    public static function requireAdmin(): void
    {
        self::requireLogin(); if (!self::isAdmin()) { http_response_code(403); echo 'Forbidden'; exit; }
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf'])) { $_SESSION['_csrf'] = bin2hex(random_bytes(32)); }
        return $_SESSION['_csrf'];
    }
    public static function checkCsrf(?string $token): bool
    { return isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], (string)$token); }
}
