<?php

namespace App\Models;

class Book extends Model
{
    static $resultLimit = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($page = 1)
    {
        $offset = ($page - 1) * self::$resultLimit;

        $query = self::$pdo->prepare('SELECT * FROM books ORDER BY id DESC LIMIT :limit OFFSET :offset');
        $query->bindParam(':limit', self::$resultLimit, \PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get($id)
    {
        $statement = self::$pdo->prepare("SELECT * FROM books WHERE id = :id");
        $statement->execute(["id" => $id]);
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($book)
    {
        $query = "INSERT INTO books (title, author) VALUES (:title, :author)";
        $statement = self::$pdo->prepare($query);
        $statement->execute([
            "title" => $book["title"],
            "author" => $book["author"]
        ]);

        $newBookId = self::$pdo->lastInsertId();
        return $this->get($newBookId);
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