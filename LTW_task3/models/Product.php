<?php
namespace App\Models;

use Core\Database;
use PDO;

class Product
{
    /**
     * Get all products with pagination and search
     * @param array $filters ['search', 'limit', 'offset']
     * @return array
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT * FROM PRODUCT WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR description LIKE :search2)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
        }

        // Schema: create_at (not created_at)
        $sql .= " ORDER BY create_at DESC";

        if (isset($filters['limit'])) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = Database::conn()->prepare($sql);

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
     * Count total products
     * @param array $filters
     * @return int
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM PRODUCT WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR description LIKE :search2)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
        }

        $stmt = Database::conn()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get single product by ID
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM PRODUCT WHERE ID = :id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create new product
     * @param array $data
     * @return int Last insert ID
     */
    public static function create(array $data): int
    {
        $sql = "INSERT INTO PRODUCT (name, price, description, image, stock_quantity) VALUES (:name, :price, :description, :image, :stock_quantity)";

        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':description' => $data['description'] ?? null,
            ':image' => $data['image'] ?? null,
            ':stock_quantity' => $data['stock_quantity'] ?? 0,
        ]);

        return (int) Database::conn()->lastInsertId();
    }

    /**
     * Update product
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        // FIX: Only update image if new image is provided
        $sql = "UPDATE PRODUCT SET name = :name, price = :price, description = :description, stock_quantity = :stock_quantity";

        $params = [
            ':id' => $id,
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':description' => $data['description'] ?? null,
            ':stock_quantity' => $data['stock_quantity'] ?? 0,
        ];

        // Add image only if exists
        if (!empty($data['image'])) {
            $sql .= ", image = :image";
            $params[':image'] = $data['image'];
        }

        $sql .= " WHERE ID = :id";

        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete product
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM PRODUCT WHERE ID = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}