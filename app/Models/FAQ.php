<?php
namespace App\Models;

use Core\Database;
use PDO;

class FAQ
{
    /**
     * Get all FAQs with pagination and search
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT * FROM faqs WHERE 1=1";
        $params = [];

        // Search
        if (!empty($filters['search'])) {
            $sql .= " AND (question LIKE :search OR answer LIKE :search2)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }

        $sql .= " ORDER BY display_order ASC, created_at DESC";

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
     * Count total FAQs
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM faqs WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (question LIKE :search OR answer LIKE :search2)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
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
     * Get single FAQ by ID
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM faqs WHERE id = :id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create new FAQ
     */
    public static function create(array $data): int
    {
        $sql = "INSERT INTO faqs (question, answer, display_order, status, created_at) 
                VALUES (:question, :answer, :display_order, :status, NOW())";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
            ':question' => $data['question'],
            ':answer' => $data['answer'],
            ':display_order' => $data['display_order'] ?? 0,
            ':status' => $data['status'] ?? 'active',
        ]);
        return (int)Database::conn()->lastInsertId();
    }

    /**
     * Update FAQ
     */
    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE faqs SET question = :question, answer = :answer, 
                display_order = :display_order, status = :status, updated_at = NOW() 
                WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':question' => $data['question'],
            ':answer' => $data['answer'],
            ':display_order' => $data['display_order'] ?? 0,
            ':status' => $data['status'] ?? 'active',
        ]);
    }

    /**
     * Delete FAQ
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM faqs WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}

