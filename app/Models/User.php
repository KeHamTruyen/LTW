<?php
namespace App\Models;

use Core\Database;
use PDO;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $sql = "INSERT INTO users (name, email, password_hash, created_at) VALUES (:name, :email, :password_hash, NOW())";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash'],
        ]);
        return (int)Database::conn()->lastInsertId();
    }
}
