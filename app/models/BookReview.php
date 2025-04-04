<?php

namespace App\Models;

class BookReview extends Model
{
    public function getByBookId($bookId)
    {
        $stmt = self::$pdo->prepare("
            SELECT r.*, u.name as user_name 
            FROM book_reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.book_id = :book_id
            ORDER BY r.id DESC
        ");
        $stmt->execute(['book_id' => $bookId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = self::$pdo->prepare("
            INSERT INTO book_reviews (book_id, user_id, comment, rating) 
            VALUES (:book_id, :user_id, :comment, :rating)
        ");
        return $stmt->execute([
            'book_id' => $data['book_id'],
            'user_id' => $data['user_id'],
            'comment' => $data['comment'],
            'rating'  => $data['rating'] ?? null // Можно сделать не обязательным
        ]);
    }

    public function delete($id)
    {
        $stmt = self::$pdo->prepare("DELETE FROM book_reviews WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
