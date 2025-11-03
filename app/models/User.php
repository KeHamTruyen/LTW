<?php
namespace App\Models;

use Core\Database;
use PDO;

class User
{
    public static function create(string $name, string $email, string $password, ?string $phone = null, string $role = 'customer'): int
    {
        $pdo = Database::conn();
        $stmt = $pdo->prepare('INSERT INTO users(name, email, phone, password_hash, role) VALUES(?,?,?,?,?)');
        $stmt->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT), $role]);
        return (int)$pdo->lastInsertId();
    }

    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::conn();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::conn();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function updateProfile(int $id, string $name, ?string $phone, ?string $avatarUrl): bool
    {
        $pdo = Database::conn();
        $stmt = $pdo->prepare('UPDATE users SET name = ?, phone = ?, avatar_url = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        return $stmt->execute([$name, $phone, $avatarUrl, $id]);
    }

    public static function updatePassword(int $id, string $newPassword): bool
    {
        $pdo = Database::conn();
        $stmt = $pdo->prepare('UPDATE users SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        return $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
    }

    public static function listUsers(?string $q = null): array
    {
        $pdo = Database::conn();
        if ($q) {
            $like = '%'.$q.'%';
            $stmt = $pdo->prepare('SELECT id,name,email,phone,role,status,created_at FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC');
            $stmt->execute([$like, $like]);
        } else {
            $stmt = $pdo->query('SELECT id,name,email,phone,role,status,created_at FROM users ORDER BY created_at DESC');
        }
        return $stmt->fetchAll();
    }

    public static function setStatus(int $id, string $status): bool
    {
        $pdo = Database::conn();
        $stmt = $pdo->prepare('UPDATE users SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public static function setRole(int $id, string $role): bool
    {
        $pdo = Database::conn();
        $stmt = $pdo->prepare('UPDATE users SET role = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        return $stmt->execute([$role, $id]);
    }

    public static function resetPassword(int $id): string
    {
        $newPass = bin2hex(random_bytes(4)); // 8 hex chars
        self::updatePassword($id, $newPass);
        return $newPass;
    }
}
