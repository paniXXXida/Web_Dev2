<?php

namespace App\Models;

require_once 'Model.php';

class BookRequest extends Model
{
    protected string $table = "book_requests";

    public function findByUserId(int $userId): array
    {
        $sql = "SELECT br.*, b.title AS book FROM {$this->table} br
                JOIN books b ON br.book_id = b.id
                WHERE br.user_id = :user_id
                ORDER BY br.requested_at DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (user_id, book_id, status) VALUES (:user_id, :book_id, :status)";
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':book_id' => $data['book_id'],
            ':status'  => $data['status'] ?? 'pending'
        ]);
    }
}
