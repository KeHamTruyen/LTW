<?php
namespace App\Models;

use Core\Database;
use PDO;

class Cart
{
    /**
     * Get cart items for user
     */
    public static function getItems(int $userId): array
    {
        $sql = "SELECT ci.*, p.name, p.price, p.sale_price, p.image_url, p.stock_quantity, p.slug
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.user_id = :user_id
                ORDER BY ci.created_at DESC";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get cart total
     */
    public static function getTotal(int $userId): float
    {
        $items = self::getItems($userId);
        $total = 0;
        foreach ($items as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $total += $price * $item['quantity'];
        }
        return $total;
    }

    /**
     * Add item to cart
     */
    public static function addItem(int $userId, int $productId, int $quantity): bool
    {
        // Check if item already exists
        $sql = "SELECT id, quantity FROM cart_items WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update quantity
            $newQuantity = $existing['quantity'] + $quantity;
            $sql = "UPDATE cart_items SET quantity = :quantity, updated_at = NOW() 
                    WHERE id = :id";
            $stmt = Database::conn()->prepare($sql);
            return $stmt->execute([
                ':quantity' => $newQuantity,
                ':id' => $existing['id'],
            ]);
        } else {
            // Insert new item
            $sql = "INSERT INTO cart_items (user_id, product_id, quantity, created_at) 
                    VALUES (:user_id, :product_id, :quantity, NOW())";
            $stmt = Database::conn()->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':product_id' => $productId,
                ':quantity' => $quantity,
            ]);
        }
    }

    /**
     * Update cart item quantity
     */
    public static function updateItem(int $userId, int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return self::removeItem($userId, $productId);
        }

        $sql = "UPDATE cart_items SET quantity = :quantity, updated_at = NOW() 
                WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':quantity' => $quantity,
        ]);
    }

    /**
     * Remove item from cart
     */
    public static function removeItem(int $userId, int $productId): bool
    {
        $sql = "DELETE FROM cart_items WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
        ]);
    }

    /**
     * Clear cart
     */
    public static function clear(int $userId): bool
    {
        $sql = "DELETE FROM cart_items WHERE user_id = :user_id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':user_id' => $userId]);
    }

    /**
     * Get cart item count
     */
    public static function getItemCount(int $userId): int
    {
        $sql = "SELECT SUM(quantity) FROM cart_items WHERE user_id = :user_id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetchColumn();
        return (int)($result ?? 0);
    }
}

