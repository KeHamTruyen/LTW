<?php
namespace App\Models;

use Core\Database;
use PDO;

class Post
{
    /**
     * Get all posts with pagination and search
     * @param array $filters ['status', 'search', 'limit', 'offset']
     * @return array
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM posts p 
                LEFT JOIN users u ON p.author_user_id = u.id 
                WHERE 1=1";
        $params = [];

        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $filters['status'];
        }

        // Search by title or content
        if (!empty($filters['search'])) {
            $sql .= " AND (p.title LIKE :search OR p.summary LIKE :search2 OR p.content_html LIKE :search3)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
            $params[':search3'] = $searchTerm;
        }

        $sql .= " ORDER BY p.created_at DESC";

        // Pagination
        if (isset($filters['limit'])) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = Database::conn()->prepare($sql);

        // Bind pagination params separately (must be int)
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
     * Count total posts (for pagination)
     * @param array $filters
     * @return int
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM posts WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE :search OR summary LIKE :search2 OR content_html LIKE :search3)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
            $params[':search3'] = $searchTerm;
        }

        $stmt = Database::conn()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get single post by ID
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM posts p 
                LEFT JOIN users u ON p.author_user_id = u.id 
                WHERE p.id = :id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Get single post by slug
     * @param string $slug
     * @return array|null
     */
    public static function findBySlug(string $slug): ?array
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM posts p 
                LEFT JOIN users u ON p.author_user_id = u.id 
                WHERE p.slug = :slug LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create new post
     * @param array $data
     * @return int Last insert ID
     */
    public static function create(array $data): int
    {
        $sql = "INSERT INTO posts (
                    author_user_id, title, slug, summary, content_html, 
                    cover_image_url, status, published_at, created_at
                ) VALUES (
                    :author_user_id, :title, :slug, :summary, :content_html, 
                    :cover_image_url, :status, :published_at, NOW()
                )";
        
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
            ':author_user_id' => $data['author_user_id'] ?? null,
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':summary' => $data['summary'] ?? null,
            ':content_html' => $data['content_html'] ?? null,
            ':cover_image_url' => $data['cover_image_url'] ?? null,
            ':status' => $data['status'] ?? 'draft',
            ':published_at' => $data['published_at'] ?? null,
        ]);
        
        return (int)Database::conn()->lastInsertId();
    }

    /**
     * Update post
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE posts SET 
                    title = :title,
                    slug = :slug,
                    summary = :summary,
                    content_html = :content_html,
                    cover_image_url = :cover_image_url,
                    status = :status,
                    published_at = :published_at,
                    updated_at = NOW()
                WHERE id = :id";
        
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':summary' => $data['summary'] ?? null,
            ':content_html' => $data['content_html'] ?? null,
            ':cover_image_url' => $data['cover_image_url'] ?? null,
            ':status' => $data['status'] ?? 'draft',
            ':published_at' => $data['published_at'] ?? null,
        ]);
    }

    /**
     * Delete post
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Generate slug from title
     * @param string $title
     * @return string
     */
    public static function generateSlug(string $title): string
    {
        // Convert to lowercase
        $slug = mb_strtolower($title, 'UTF-8');
        
        // Vietnamese character map
        $vietnameseMap = [
            'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'đ' => 'd',
            'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
        ];
        
        $slug = strtr($slug, $vietnameseMap);
        
        // Remove special characters, keep only alphanumeric and spaces
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        
        // Replace multiple spaces/hyphens with single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        
        // Trim hyphens from ends
        $slug = trim($slug, '-');
        
        return $slug;
    }

    /**
     * Check if slug exists (for validation)
     * @param string $slug
     * @param int|null $excludeId Exclude this ID (for update)
     * @return bool
     */
    public static function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM posts WHERE slug = :slug";
        $params = [':slug' => $slug];
        
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Get previous published post
     * @param int $id
     * @param string $publishedAt
     * @return array|null
     */
    public static function getPrevious(int $id, string $publishedAt): ?array
    {
        $sql = "SELECT id, title, slug 
                FROM posts 
                WHERE status = 'published' 
                AND (published_at < :published_at OR (published_at = :published_at2 AND id < :id))
                ORDER BY published_at DESC, id DESC 
                LIMIT 1";
        
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':published_at' => $publishedAt,
            ':published_at2' => $publishedAt
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Get next published post
     * @param int $id
     * @param string $publishedAt
     * @return array|null
     */
    public static function getNext(int $id, string $publishedAt): ?array
    {
        $sql = "SELECT id, title, slug 
                FROM posts 
                WHERE status = 'published' 
                AND (published_at > :published_at OR (published_at = :published_at2 AND id > :id))
                ORDER BY published_at ASC, id ASC 
                LIMIT 1";
        
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':published_at' => $publishedAt,
            ':published_at2' => $publishedAt
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
