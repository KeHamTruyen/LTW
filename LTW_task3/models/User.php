<?php
namespace App\Models;

use Core\Database;
use PDO;

class User
{
    /**
     * Get all users with pagination and search
     * @param array $filters ['search', 'role', 'limit', 'offset']
     * @return array
     */
    public static function getAll(array $filters = []): array
    {
        // SỬA: User -> USER
        $sql = "SELECT * FROM USER WHERE 1=1";
        $params = [];

        // Search by name or email
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

        $sql .= " ORDER BY ID DESC";

        // Pagination
        if (isset($filters['limit'])) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = Database::conn()->prepare($sql);

        // Bind pagination params separately
        if (isset($filters['limit'])) {
            $stmt->bindValue(':limit', (int) $filters['limit'], PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int) ($filters['offset'] ?? 0), PDO::PARAM_INT);
        }

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count total users (for pagination)
     * @param array $filters
     * @return int
     */
    public static function count(array $filters = []): int
    {
        // SỬA: User -> USER
        $sql = "SELECT COUNT(*) FROM USER WHERE 1=1";
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

        $stmt = Database::conn()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get single user by ID
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        // SỬA: User -> USER
        $sql = "SELECT * FROM USER WHERE ID = :id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Get user by email
     * @param string $email
     * @return array|null
     */
    public static function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM USER WHERE email = :email LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create new user
     * @param array $data
     * @return int Last insert ID
     */
    public static function create(array $data): int
    {
        // SỬA: User -> USER
        $sql = "INSERT INTO USER (name, email, password, phone, role, avatar) VALUES (:name, :email, :password, :phone, :role, :avatar)";

        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            // ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':password' => $data['password'], // SỬA: Lưu mật khẩu dưới dạng plain text để dễ kiểm tra
            ':phone' => $data['phone'] ?? null,
            ':role' => $data['role'] ?? 'customer',
            ':avatar' => $data['avatar'] ?? null,
        ]);

        return (int) Database::conn()->lastInsertId();
    }

    /**
     * Update user
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        // SỬA: User -> USER
        $sql = "UPDATE USER SET name = :name, email = :email, phone = :phone, role = :role, avatar = :avatar WHERE ID = :id";

        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':role' => $data['role'] ?? 'customer',
            ':avatar' => $data['avatar'] ?? null,
        ]);
    }

    /**
     * Update password
     * @param int $id
     * @param string $password
     * @return bool
     */
    public static function updatePassword(int $id, string $password): bool
    {
        // SỬA: User -> USER
        $sql = "UPDATE USER SET password = :password WHERE ID = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    /**
     * Delete user
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        // SỬA: User -> USER
        $sql = "DELETE FROM USER WHERE ID = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Verify password
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}