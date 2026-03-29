<?php

/**
 * Modèle Image
 */

require_once __DIR__ . '/../config/database.php';

class Image
{
    private static function db(): PDO
    {
        return getConnection();
    }

    /**
     * Récupère toutes les images d'un article
     */
    public static function findByArticleId(int $articleId): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("SELECT * FROM images WHERE article_id = ? ORDER BY date_creation DESC");
        $stmt->execute([$articleId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère une image par son ID
     */
    public static function findById(int $id): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("SELECT * FROM images WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Crée une nouvelle image
     */
    public static function create(string $fichier, string $alt, ?int $articleId): int
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("INSERT INTO images (fichier, alt, article_id) VALUES (?, ?, ?)");
        $stmt->execute([$fichier, $alt, $articleId]);
        return (int) $pdo->lastInsertId();
    }

    /**
     * Supprime une image
     */
    public static function delete(int $id): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
        $stmt->execute([$id]);
    }

    /**
     * Compte le nombre total d'images
     */
    public static function count(): int
    {
        $pdo = self::db();
        return (int) $pdo->query("SELECT COUNT(*) FROM images")->fetchColumn();
    }
}
