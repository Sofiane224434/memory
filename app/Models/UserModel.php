<?php

namespace App\Models;

use Core\Database;
use PDO;

class UserModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function create(string $username, string $passwordHash): int|false
    {
        $sql = "INSERT INTO users (username, password_hash, created_at) VALUES (:username, :password_hash, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'password_hash' => $passwordHash
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Trouver un utilisateur par son nom d'utilisateur
     */
    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT id, username, password_hash, created_at FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Trouver un utilisateur par son ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT id, username, created_at FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
