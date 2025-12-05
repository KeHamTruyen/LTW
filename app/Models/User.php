<?php
namespace App\Models;

use Core\Database;
use PDO;

class User
{
    /**
     * Find user by email
     */
    public static function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Find user by ID
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Get all users with filters
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        // Search
        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR email LIKE :search2)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
        }

        // Filter by role
        if (!empty($filters['role'])) {
            $sql .= " AND role = :role";
            $params[':role'] = $filters['role'];
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }

        $sql .= " ORDER BY created_at DESC";

        // Pagination
        if (isset($filters['limit'])) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = Database::conn()->prepare($sql);

        if (isset($filters['limit'])) {
            $stmt->bindValue(':limit', (int)$filters['limit'], PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)($filters['offset'] ?? 0), PDO::PARAM_INT);
        }

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count users
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM users WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR email LIKE :search2)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
        }

        if (!empty($filters['role'])) {
            $sql .= " AND role = :role";
            $params[':role'] = $filters['role'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }

        $stmt = Database::conn()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Create user
     */
    public static function create(array $data): int
    {
        $sql = "INSERT INTO users (name, email, phone, password_hash, role, status, created_at) 
                VALUES (:name, :email, :phone, :password_hash, :role, :status, NOW())";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':password_hash' => $data['password_hash'],
            ':role' => $data['role'] ?? 'customer',
            ':status' => $data['status'] ?? 'active',
        ]);
        return (int)Database::conn()->lastInsertId();
    }

    /**
     * Update user
     */
    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE users SET name = :name, email = :email, phone = :phone, 
                avatar_url = :avatar_url, updated_at = NOW() WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':avatar_url' => $data['avatar_url'] ?? null,
        ]);
    }

    /**
     * Update password
     */
    public static function updatePassword(int $id, string $passwordHash): bool
    {
        $sql = "UPDATE users SET password_hash = :password_hash, updated_at = NOW() WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':password_hash' => $passwordHash,
        ]);
    }

    /**
     * Update status
     */
    public static function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE users SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status,
        ]);
    }

    /**
     * Update role
     */
    public static function updateRole(int $id, string $role): bool
    {
        $sql = "UPDATE users SET role = :role, updated_at = NOW() WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':role' => $role,
        ]);
    }

    /**
     * Delete user
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Update last login
     */
    public static function updateLastLogin(int $id): bool
    {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
