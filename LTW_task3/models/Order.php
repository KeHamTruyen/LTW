<?php
namespace App\Models;

use Core\Database;
use PDO;

class Order
{
    /**
     * Get all orders with pagination and filters
     * @param array $filters ['user_id', 'status', 'limit', 'offset']
     * @return array
     */
    public static function getAll(array $filters = []): array
    {
        // Schema: Orders, USER_id, USER.ID
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email
                FROM Orders o
                LEFT JOIN USER u ON o.USER_id = u.ID
                WHERE 1=1";
        $params = [];

        // Filter by user
        if (!empty($filters['user_id'])) {
            $sql .= " AND o.USER_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }

        // Filter by status (Schema: Status)
        if (!empty($filters['status'])) {
            $sql .= " AND o.Status = :status";
            $params[':status'] = $filters['status'];
        }

        // Schema: Date
        $sql .= " ORDER BY o.Date DESC";

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
     * Count total orders (for pagination)
     * @param array $filters
     * @return int
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM Orders WHERE 1=1";
        $params = [];

        if (!empty($filters['user_id'])) {
            $sql .= " AND USER_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND Status = :status";
            $params[':status'] = $filters['status'];
        }

        $stmt = Database::conn()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get single order by ID
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email
                FROM Orders o
                LEFT JOIN USER u ON o.USER_id = u.ID
                WHERE o.ID = :id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create new order from cart AND update stock
     * @param int $userId
     * @param int $cartId
     * @return int Order ID
     */
    public static function createFromCart(int $userId, int $cartId): int
    {
        $db = Database::conn();

        // 1. Get cart items to check stock
        $cartItems = Cart::getItems($cartId);
        if (empty($cartItems)) {
            throw new \Exception('Giỏ hàng trống');
        }

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item['stock_quantity'] < $item['Quantity']) {
                throw new \Exception("Sản phẩm {$item['name']} không đủ hàng (Còn: {$item['stock_quantity']})");
            }
        }

        try {
            $db->beginTransaction();

            $total = Cart::getTotal($cartId);

            // 2. Create Order (Schema: USER_id, Status, Date)
            $sql = "INSERT INTO Orders (USER_id, total_amount, Status, Date) VALUES (:user_id, :total, 'pending', NOW())";
            $stmt = $db->prepare($sql);
            $stmt->execute([':user_id' => $userId, ':total' => $total]);
            $orderId = (int) $db->lastInsertId();

            // 3. Create Order Details AND Deduct Stock
            $sqlDetail = "INSERT INTO ORDER_DETAIL (order_id, PRODUCT_id, quantity, price_at_order) VALUES (:order_id, :product_id, :quantity, :price)";
            // Cập nhật tồn kho
            $sqlStock = "UPDATE PRODUCT SET stock_quantity = stock_quantity - :qty WHERE ID = :product_id";

            $stmtDetail = $db->prepare($sqlDetail);
            $stmtStock = $db->prepare($sqlStock);

            foreach ($cartItems as $item) {
                // Insert Detail
                $stmtDetail->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['PRODUCT_id'],
                    ':quantity' => $item['Quantity'],
                    ':price' => $item['price'],
                ]);

                // Update Stock
                $stmtStock->execute([
                    ':qty' => $item['Quantity'],
                    ':product_id' => $item['PRODUCT_id']
                ]);
            }

            // 4. Clear cart
            Cart::clear($cartId);

            $db->commit();
            return $orderId;
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Update order status
     * @param int $id
     * @param string $status
     * @return bool
     */
    public static function updateStatus(int $id, string $status): bool
    {
        // Schema: Status
        $sql = "UPDATE Orders SET Status = :status WHERE ID = :id";
        $stmt = Database::conn()->prepare($sql);
        return $stmt->execute([':id' => $id, ':status' => $status]);
    }

    /**
     * Get order details
     * @param int $orderId
     * @return array
     */
    public static function getDetails(int $orderId): array
    {
        // Schema: ORDER_DETAIL, PRODUCT_id
        $sql = "SELECT od.*, p.name, p.image
                FROM ORDER_DETAIL od
                JOIN PRODUCT p ON od.PRODUCT_id = p.ID
                WHERE od.order_id = :order_id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get payment info
     * @param int $orderId
     * @return array|null
     */
    public static function getPayment(int $orderId): ?array
    {
        $sql = "SELECT * FROM PAYMENT WHERE order_id = :order_id LIMIT 1";
        $stmt = Database::conn()->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}