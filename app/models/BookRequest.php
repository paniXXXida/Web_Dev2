<?php

namespace App\Models;

class BookRequest extends Model
{
    public function create($data)
    {
        $stmt = self::$pdo->prepare("INSERT INTO book_requests (user_id, book_id, status) VALUES (:user_id, :book_id, :status)");
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'book_id' => $data['book_id'],
            'status'  => $data['status'] ?? 'pending'
        ]);
    }

    public function updateStatus($id, $status)
    {
        $stmt = self::$pdo->prepare("UPDATE book_requests SET status = :status WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
        ]);
    }


    public function findByUserIdWithBookTitles($userId)
    {
        $stmt = self::$pdo->prepare("SELECT br.*, b.title AS book_title FROM book_requests br JOIN books b ON br.book_id = b.id WHERE br.user_id = :user_id ORDER BY br.id DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function cancelRequest($id, $userId)
    {
        $stmt = self::$pdo->prepare("UPDATE book_requests SET status = 'cancelled' WHERE id = :id AND user_id = :user_id AND status = 'pending'");
        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    public function getAll()
    {
        $stmt = self::$pdo->query("
        SELECT br.*, u.name AS user_name, b.title AS book_title 
        FROM book_requests br 
        JOIN users u ON br.user_id = u.id 
        JOIN books b ON br.book_id = b.id 
        ORDER BY br.id DESC
    ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
