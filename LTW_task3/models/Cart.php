<?php
namespace App\Models;

use Core\Database;
use PDO;

class Cart
{
    /**
     * Get or create cart for user
     * @param int $userId
     * @return int Cart ID
     */
    public static function getOrCreate(int $userId): int
    {
        // Schema: USER_id
        $sql = "SELECT ID FROM Cart WHERE USER_id = :user_id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            return (int) $cart['ID'];
        }

        // Create new cart
        $sql = "INSERT INTO Cart (USER_id) VALUES (:user_id)";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return (int) Database::conn()->lastInsertId();
    }

    /**
     * Get cart items
     * @param int $cartId
     * @return array
     */
    public static function getItems(int $cartId): array
    {
        // Schema: CART_ITEM, PRODUCT_id, PRODUCT.ID
        $sql = "SELECT ci.*, p.name, p.price, p.image, p.stock_quantity
                FROM CART_ITEM ci
                JOIN PRODUCT p ON ci.PRODUCT_id = p.ID
                WHERE ci.cart_id = :cart_id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':cart_id' => $cartId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add item to cart
     * @param int $cartId
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public static function addItem(int $cartId, int $productId, int $quantity): bool
    {
        // Schema: Quantity, PRODUCT_id
        $sql = "SELECT Quantity FROM CART_ITEM WHERE cart_id = :cart_id AND PRODUCT_id = :product_id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':cart_id' => $cartId, ':product_id' => $productId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update quantity
            $newQuantity = $existing['Quantity'] + $quantity;
            $sql = "UPDATE CART_ITEM SET Quantity = :quantity WHERE cart_id = :cart_id AND PRODUCT_id = :product_id";
            $stmt = Database::conn()->prepare($sql);
            return $stmt->execute([
                ':quantity' => $newQuantity,
                ':cart_id' => $cartId,
                ':product_id' => $productId,
            ]);
        } else {
            // Insert new item
            $sql = "INSERT INTO CART_ITEM (cart_id, PRODUCT_id, Quantity) VALUES (:cart_id, :product_id, :quantity)";
            $stmt = Database::conn()->prepare($sql);
            return $stmt->execute([
                ':cart_id' => $cartId,
                ':product_id' => $productId,
                ':quantity' => $quantity,
            ]);
        }
    }

    /**
     * Update item quantity
     * @param int $cartId
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public static function updateItem(int $cartId, int $productId, int $quantity): bool
    {
        // Schema: Quantity, PRODUCT_id
        $sql = "UPDATE CART_ITEM SET Quantity = :quantity WHERE cart_id = :cart_id AND PRODUCT_id = :product_id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':quantity' => $quantity,
            ':cart_id' => $cartId,
            ':product_id' => $productId,
        ]);
    }

    /**
     * Remove item from cart
     * @param int $cartId
     * @param int $productId
     * @return bool
     */
    public static function removeItem(int $cartId, int $productId): bool
    {
        $sql = "DELETE FROM CART_ITEM WHERE cart_id = :cart_id AND PRODUCT_id = :product_id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':cart_id' => $cartId, ':product_id' => $productId]);
    }

    /**
     * Clear cart
     * @param int $cartId
     * @return bool
     */
    public static function clear(int $cartId): bool
    {
        $sql = "DELETE FROM CART_ITEM WHERE cart_id = :cart_id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':cart_id' => $cartId]);
    }

    /**
     * Get cart total
     * @param int $cartId
     * @return float
     */
    public static function getTotal(int $cartId): float
    {
        // Schema: Quantity, PRODUCT_id
        $sql = "SELECT SUM(ci.Quantity * p.price) as total
                FROM CART_ITEM ci
                JOIN PRODUCT p ON ci.PRODUCT_id = p.ID
                WHERE ci.cart_id = :cart_id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':cart_id' => $cartId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) ($result['total'] ?? 0);
    }

    /**
     * Get cart item count
     * @param int $cartId
     * @return int
     */
    public static function getItemCount(int $cartId): int
    {
        $sql = "SELECT SUM(Quantity) as count FROM CART_ITEM WHERE cart_id = :cart_id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':cart_id' => $cartId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }
}