<?php

namespace App\Models;

class Book extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $query = self::$pdo->prepare('SELECT * FROM books ORDER BY id DESC');
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get($id)
    {
        $statement = self::$pdo->prepare("SELECT * FROM books WHERE id = :id");
        $statement->execute(["id" => $id]);
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = self::$pdo->prepare("
            INSERT INTO books (title, author, description)
            VALUES (:title, :author, :description)
        ");
        return $stmt->execute([
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data['description'] ?? ''
        ]);
    }

    public function update($id, $book)
    {
        $query = "UPDATE books SET title = :title, author = :author WHERE id = :id";
        $statement = self::$pdo->prepare($query);
        $statement->execute([
            "id" => $id,
            "title" => $book["title"],
            "author" => $book["author"]
        ]);

        return $this->get($id);
    }

    public function delete($id)
    {
        $query = "DELETE FROM books WHERE id = :id";
        $statement = self::$pdo->prepare($query);
        $statement->execute(["id" => $id]);
    }
}