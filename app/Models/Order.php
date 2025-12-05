<?php
namespace App\Models;

use Core\Database;
use PDO;

class Order
{
    /**
     * Get all orders with filters
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE 1=1";
        $params = [];

        // Filter by user
        if (!empty($filters['user_id'])) {
            $sql .= " AND o.user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND o.status = :status";
            $params[':status'] = $filters['status'];
        }

        // Search
        if (!empty($filters['search'])) {
            $sql .= " AND (o.order_number LIKE :search OR o.customer_name LIKE :search2 OR o.customer_email LIKE :search3)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
            $params[':search3'] = $searchTerm;
        }

        $sql .= " ORDER BY o.created_at DESC";

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
     * Count orders
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM orders WHERE 1=1";
        $params = [];

        if (!empty($filters['user_id'])) {
            $sql .= " AND user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
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
     * Get order by ID
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE o.id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Get order items
     */
    public static function getItems(int $orderId): array
    {
        $sql = "SELECT oi.*, p.slug as product_slug 
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id
                ORDER BY oi.id ASC";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create order from cart
     */
    public static function createFromCart(int $userId, array $cartItems, array $orderData): int
    {
        $pdo = Database::conn();
        $pdo->beginTransaction();

        try {
            // Generate order number
            $orderNumber = 'ORD' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Calculate total
            $total = 0;
            foreach ($cartItems as $item) {
                $price = $item['sale_price'] ?? $item['price'];
                $total += $price * $item['quantity'];
            }

            // Create order
            $sql = "INSERT INTO orders (order_number, user_id, customer_name, customer_email, 
                    customer_phone, shipping_address, total_amount, status, payment_method, 
                    payment_status, notes, created_at) 
                    VALUES (:order_number, :user_id, :customer_name, :customer_email, 
                    :customer_phone, :shipping_address, :total_amount, :status, :payment_method, 
                    :payment_status, :notes, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':order_number' => $orderNumber,
                ':user_id' => $userId,
                ':customer_name' => $orderData['customer_name'],
                ':customer_email' => $orderData['customer_email'],
                ':customer_phone' => $orderData['customer_phone'],
                ':shipping_address' => $orderData['shipping_address'],
                ':total_amount' => $total,
                ':status' => 'pending',
                ':payment_method' => $orderData['payment_method'] ?? null,
                ':payment_status' => 'pending',
                ':notes' => $orderData['notes'] ?? null,
            ]);

            $orderId = (int)$pdo->lastInsertId();

            // Create order items
            foreach ($cartItems as $item) {
                $price = $item['sale_price'] ?? $item['price'];
                $subtotal = $price * $item['quantity'];

                $sql = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) 
                        VALUES (:order_id, :product_id, :product_name, :product_price, :quantity, :subtotal)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['product_id'],
                    ':product_name' => $item['name'],
                    ':product_price' => $price,
                    ':quantity' => $item['quantity'],
                    ':subtotal' => $subtotal,
                ]);

                // Update product stock
                $newStock = $item['stock_quantity'] - $item['quantity'];
                $sql = "UPDATE products SET stock_quantity = :stock WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':stock' => $newStock,
                    ':id' => $item['product_id'],
                ]);
            }

            // Clear cart
            Cart::clear($userId);

            $pdo->commit();
            return $orderId;
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Update order status
     */
    public static function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status,
        ]);
    }

    /**
     * Update payment status
     */
    public static function updatePaymentStatus(int $id, string $status): bool
    {
        $sql = "UPDATE orders SET payment_status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status,
        ]);
    }
}

