<?php
namespace App\Models;

use Core\Database;
use PDO;

class Page
{
    /**
     * Get all pages
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT p.*, u.name as updated_by_name 
                FROM pages p 
                LEFT JOIN users u ON p.updated_by = u.id 
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (p.title LIKE :search OR p.slug LIKE :search2)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
        }

        $sql .= " ORDER BY p.title ASC";

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
     * Count pages
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM pages WHERE 1=1";
        $params = [];

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
     * Get page by slug
     */
    public static function findBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM pages WHERE slug = :slug AND status = 'published'";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Get page by ID
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT p.*, u.name as updated_by_name 
                FROM pages p 
                LEFT JOIN users u ON p.updated_by = u.id 
                WHERE p.id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Create page
     */
    public static function create(array $data): int
    {
        $sql = "INSERT INTO pages (slug, title, content_html, meta_title, meta_description, status, updated_by) 
                VALUES (:slug, :title, :content_html, :meta_title, :meta_description, :status, :updated_by)";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':slug', $data['slug']);
        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':content_html', $data['content_html'] ?? null);
        $stmt->bindValue(':meta_title', $data['meta_title'] ?? null);
        $stmt->bindValue(':meta_description', $data['meta_description'] ?? null);
        $stmt->bindValue(':status', $data['status'] ?? 'published');
        $stmt->bindValue(':updated_by', $data['updated_by'] ?? null, PDO::PARAM_INT);
        $stmt->execute();
        return (int)Database::conn()->lastInsertId();
    }

    /**
     * Update page
     */
    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE pages 
                SET slug = :slug, 
                    title = :title, 
                    content_html = :content_html, 
                    meta_title = :meta_title, 
                    meta_description = :meta_description, 
                    status = :status, 
                    updated_by = :updated_by,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':slug', $data['slug']);
        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':content_html', $data['content_html'] ?? null);
        $stmt->bindValue(':meta_title', $data['meta_title'] ?? null);
        $stmt->bindValue(':meta_description', $data['meta_description'] ?? null);
        $stmt->bindValue(':status', $data['status'] ?? 'published');
        $stmt->bindValue(':updated_by', $data['updated_by'] ?? null, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Delete page
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM pages WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

