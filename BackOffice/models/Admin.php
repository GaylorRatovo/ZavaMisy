<?php

/**
 * Modèle Admin
 */

require_once __DIR__ . '/../config/database.php';

class Admin
{
    private static function db(): PDO
    {
        return getConnection();
    }

    /**
     * Authentifie un administrateur par username et password
     */
    public static function authenticate(string $username, string $password): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Récupère un admin par son ID
     */
    public static function findById(int $id): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
