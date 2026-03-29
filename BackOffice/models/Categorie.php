<?php

/**
 * Modèle Categorie
 */

require_once __DIR__ . '/../config/database.php';

class Categorie
{
    private static function db(): PDO
    {
        return getConnection();
    }

    /**
     * Récupère toutes les catégories avec le nombre d'articles
     */
    public static function findAll(): array
    {
        $pdo = self::db();
        return $pdo->query("
            SELECT c.*, COUNT(a.id) as nb_articles
            FROM categories c
            LEFT JOIN articles a ON c.id = a.categorie_id
            GROUP BY c.id
            ORDER BY c.nom
        ")->fetchAll();
    }

    /**
     * Récupère toutes les catégories (liste simple)
     */
    public static function findAllSimple(): array
    {
        $pdo = self::db();
        return $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
    }

    /**
     * Récupère une catégorie par son ID
     */
    public static function findById(int $id): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Crée une nouvelle catégorie
     */
    public static function create(string $nom, string $slug): int
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("INSERT INTO categories (nom, slug) VALUES (?, ?)");
        $stmt->execute([$nom, $slug]);
        return (int) $pdo->lastInsertId();
    }

    /**
     * Met à jour une catégorie
     */
    public static function update(int $id, string $nom, string $slug): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("UPDATE categories SET nom = ?, slug = ? WHERE id = ?");
        $stmt->execute([$nom, $slug, $id]);
    }

    /**
     * Supprime une catégorie
     */
    public static function delete(int $id): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
    }

    /**
     * Compte le nombre de catégories
     */
    public static function count(): int
    {
        $pdo = self::db();
        return (int) $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    }
}
