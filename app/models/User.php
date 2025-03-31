<?php

namespace App\Models;

require_once 'Model.php';

class User extends Model
{
    protected string $table = "users";

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = self::$pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute([
            ':name'     => $data['name'],
            ':email'    => $data['email'],
            ':password' => $data['password'],
            ':role'     => $data['role'] ?? 'customer',
        ]);
    }

    public function findById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
