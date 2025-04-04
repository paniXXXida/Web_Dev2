<?php

namespace App\Models;

class AdminModel extends Model
{
    public function getAllUsers()
    {
        $query = self::$pdo->query("SELECT id, name, email, role FROM users ORDER BY id DESC");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteUser($id)
    {
        $stmt = self::$pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(["id" => $id]);
    }

    public function updateUserRole($id, $role)
    {
        $stmt = self::$pdo->prepare("UPDATE users SET role = :role WHERE id = :id");
        return $stmt->execute([
            "id" => $id,
            "role" => $role
        ]);
    }
}
