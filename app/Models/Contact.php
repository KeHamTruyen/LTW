<?php
namespace App\Models;

use Core\Database;
use PDO;

class Contact
{
    /**
     * Get all contacts with pagination and filters
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT c.*, u.name as replied_by_name 
                FROM contacts c 
                LEFT JOIN users u ON c.replied_by = u.id 
                WHERE 1=1";
        $params = [];

        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND c.status = :status";
            $params[':status'] = $filters['status'];
        }

        // Search
        if (!empty($filters['search'])) {
            $sql .= " AND (c.name LIKE :search OR c.email LIKE :search2 OR c.subject LIKE :search3)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
            $params[':search3'] = $searchTerm;
        }

        $sql .= " ORDER BY c.created_at DESC";

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
     * Count total contacts
     */
    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) FROM contacts WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR email LIKE :search2 OR subject LIKE :search3)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search'] = $searchTerm;
            $params[':search2'] = $searchTerm;
            $params[':search3'] = $searchTerm;
        }

        $stmt = Database::conn()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get contact by ID
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT c.*, u.name as replied_by_name 
                FROM contacts c 
                LEFT JOIN users u ON c.replied_by = u.id 
                WHERE c.id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Create new contact
     */
    public static function create(array $data): int
    {
        $sql = "INSERT INTO contacts (name, email, phone, subject, message, ip_address) 
                VALUES (:name, :email, :phone, :subject, :message, :ip_address)";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':phone', $data['phone'] ?? null);
        $stmt->bindValue(':subject', $data['subject'] ?? null);
        $stmt->bindValue(':message', $data['message']);
        $stmt->bindValue(':ip_address', $data['ip_address'] ?? null);
        $stmt->execute();
        return (int)Database::conn()->lastInsertId();
    }

    /**
     * Update contact status
     */
    public static function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE contacts SET status = :status WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Reply to contact
     */
    public static function reply(int $id, int $userId, string $replyMessage): bool
    {
        $sql = "UPDATE contacts 
                SET status = 'replied', 
                    replied_by = :user_id, 
                    reply_message = :reply_message, 
                    replied_at = NOW() 
                WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':reply_message', $replyMessage);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Delete contact
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM contacts WHERE id = :id";
        $stmt = Database::conn()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get unread count
     */
    public static function getUnreadCount(): int
    {
        $sql = "SELECT COUNT(*) FROM contacts WHERE status = 'unread'";
        $stmt = Database::conn()->query($sql);
        return (int)$stmt->fetchColumn();
    }
}

