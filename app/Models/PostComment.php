<?php
namespace App\Models;

use Core\Database;
use PDO;

class PostComment
{
    /**
     * Get comments for a post
     * @param int $postId
     * @param array $filters ['status', 'limit', 'offset']
     * @return array
     */
    public static function getByPost(int $postId, array $filters = []): array
    {
        $sql = "SELECT c.*, u.name as user_name, u.avatar_url 
                FROM post_comments c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE c.post_id = :post_id";
        $params = [':post_id' => $postId];

        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND c.status = :status";
            $params[':status'] = $filters['status'];
        }

        $sql .= " ORDER BY c.created_at DESC";

        // Pagination
        if (isset($filters['limit'])) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = Database::conn()->prepare($sql);

        // Bind pagination params
        if (isset($filters['limit'])) {
            $stmt->bindValue(':limit', (int)$filters['limit'], PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)($filters['offset'] ?? 0), PDO::PARAM_INT);
        }

        // Bind other params
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all comments (for admin)
     * @param array $filters ['status', 'post_id', 'limit', 'offset']
     * @return array
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT c.*, p.title as post_title, u.name as user_name 
                FROM post_comments c 
                LEFT JOIN posts p ON c.post_id = p.id 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND c.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['post_id'])) {
            $sql .= " AND c.post_id = :post_id";
            $params[':post_id'] = $filters['post_id'];
        }

        $sql .= " ORDER BY c.created_at DESC";

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
     * Count comments
     * @param array $filters
     * @return int
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM post_comments WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['post_id'])) {
            $sql .= " AND post_id = :post_id";
            $params[':post_id'] = $filters['post_id'];
        }

        $stmt = Database::conn()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get single comment by ID
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT c.*, p.title as post_title, u.name as user_name 
                FROM post_comments c 
                LEFT JOIN posts p ON c.post_id = p.id 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE c.id = :id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create new comment
     * @param array $data
     * @return int
     */
    public static function create(array $data): int
    {
        $sql = "INSERT INTO post_comments (
                    post_id, user_id, author_name, author_email, 
                    rating, content, status, ip_address, created_at
                ) VALUES (
                    :post_id, :user_id, :author_name, :author_email, 
                    :rating, :content, :status, :ip_address, NOW()
                )";
        
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
            ':post_id' => $data['post_id'],
            ':user_id' => $data['user_id'] ?? null,
            ':author_name' => $data['author_name'] ?? null,
            ':author_email' => $data['author_email'] ?? null,
            ':rating' => $data['rating'] ?? null,
            ':content' => $data['content'],
            ':status' => $data['status'] ?? 'pending',
            ':ip_address' => $data['ip_address'] ?? null,
        ]);
        
        return (int)Database::conn()->lastInsertId();
    }

    /**
     * Update comment status
     * @param int $id
     * @param string $status
     * @return bool
     */
    public static function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE post_comments SET status = :status WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':id' => $id, ':status' => $status]);
    }

    /**
     * Delete comment
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM post_comments WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get average rating for a post
     * @param int $postId
     * @return float
     */
    public static function getAverageRating(int $postId): float
    {
        $sql = "SELECT AVG(rating) FROM post_comments 
                WHERE post_id = :post_id AND rating IS NOT NULL AND status = 'approved'";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':post_id' => $postId]);
        return (float)$stmt->fetchColumn();
    }

    /**
     * Get rating count for a post
     * @param int $postId
     * @return int
     */
    public static function getRatingCount(int $postId): int
    {
        $sql = "SELECT COUNT(*) FROM post_comments 
                WHERE post_id = :post_id AND rating IS NOT NULL AND status = 'approved'";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':post_id' => $postId]);
        return (int)$stmt->fetchColumn();
    }
}
