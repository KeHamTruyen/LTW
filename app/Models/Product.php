<?php
namespace App\Models;

use Core\Database;
use PDO;

class Product
{
    /**
     * Get all products with filters and pagination
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        $params = [];

        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $filters['status'];
        }

        // Filter by category
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        // Filter featured
        if (isset($filters['featured']) && $filters['featured'] !== '') {
            $sql .= " AND p.featured = :featured";
            $params[':featured'] = $filters['featured'] ? 1 : 0;
        }

        // Search
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE :search OR p.description LIKE :search2 OR p.sku LIKE :search3)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
            $params[':search3'] = $searchTerm;
        }

        // Sort
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = strtoupper($filters['order_dir'] ?? 'DESC');
        $allowedOrder = ['created_at', 'name', 'price', 'stock_quantity'];
        if (!in_array($orderBy, $allowedOrder)) {
            $orderBy = 'created_at';
        }
        $orderDir = $orderDir === 'ASC' ? 'ASC' : 'DESC';
        $sql .= " ORDER BY p.{$orderBy} {$orderDir}";

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
     * Count products
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM products WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR description LIKE :search2)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
        }

        $stmt = Database::conn()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get product by ID
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Get product by slug
     */
    public static function findBySlug(string $slug): ?array
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.slug = :slug AND p.status = 'published'";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Create product
     */
    public static function create(array $data): int
    {
        $sql = "INSERT INTO products (name, slug, description, price, sale_price, stock_quantity, sku, 
                category_id, image_url, gallery_images, status, featured, meta_title, meta_description) 
                VALUES (:name, :slug, :description, :price, :sale_price, :stock_quantity, :sku, 
                :category_id, :image_url, :gallery_images, :status, :featured, :meta_title, :meta_description)";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':slug', $data['slug']);
        $stmt->bindValue(':description', $data['description'] ?? null);
        $stmt->bindValue(':price', $data['price']);
        $stmt->bindValue(':sale_price', $data['sale_price'] ?? null);
        $stmt->bindValue(':stock_quantity', $data['stock_quantity'] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(':sku', $data['sku'] ?? null);
        $stmt->bindValue(':category_id', $data['category_id'] ?? null, PDO::PARAM_INT);
        $stmt->bindValue(':image_url', $data['image_url'] ?? null);
        $stmt->bindValue(':gallery_images', $data['gallery_images'] ?? null);
        $stmt->bindValue(':status', $data['status'] ?? 'draft');
        $stmt->bindValue(':featured', $data['featured'] ?? false, PDO::PARAM_BOOL);
        $stmt->bindValue(':meta_title', $data['meta_title'] ?? null);
        $stmt->bindValue(':meta_description', $data['meta_description'] ?? null);
        $stmt->execute();
        return (int)Database::conn()->lastInsertId();
    }

    /**
     * Update product
     */
    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE products 
                SET name = :name, slug = :slug, description = :description, price = :price, 
                    sale_price = :sale_price, stock_quantity = :stock_quantity, sku = :sku,
                    category_id = :category_id, image_url = :image_url, gallery_images = :gallery_images,
                    status = :status, featured = :featured, meta_title = :meta_title, 
                    meta_description = :meta_description, updated_at = NOW()
                WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':slug', $data['slug']);
        $stmt->bindValue(':description', $data['description'] ?? null);
        $stmt->bindValue(':price', $data['price']);
        $stmt->bindValue(':sale_price', $data['sale_price'] ?? null);
        $stmt->bindValue(':stock_quantity', $data['stock_quantity'] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(':sku', $data['sku'] ?? null);
        $stmt->bindValue(':category_id', $data['category_id'] ?? null, PDO::PARAM_INT);
        $stmt->bindValue(':image_url', $data['image_url'] ?? null);
        $stmt->bindValue(':gallery_images', $data['gallery_images'] ?? null);
        $stmt->bindValue(':status', $data['status'] ?? 'draft');
        $stmt->bindValue(':featured', $data['featured'] ?? false, PDO::PARAM_BOOL);
        $stmt->bindValue(':meta_title', $data['meta_title'] ?? null);
        $stmt->bindValue(':meta_description', $data['meta_description'] ?? null);
        return $stmt->execute();
    }

    /**
     * Delete product
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Update stock quantity
     */
    public static function updateStock(int $id, int $quantity): bool
    {
        $sql = "UPDATE products SET stock_quantity = :quantity, updated_at = NOW() WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get featured products
     */
    public static function getFeatured(int $limit = 8): array
    {
        $sql = "SELECT * FROM products 
                WHERE featured = 1 AND status = 'published' 
                ORDER BY created_at DESC 
                LIMIT :limit";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

