<?php

/**
 * Modèle Article
 */

require_once __DIR__ . '/../config/database.php';

class Article
{
    private static function db(): PDO
    {
        return getConnection();
    }

    /**
     * Récupère tous les articles avec leur catégorie et nombre d'images
     */
    public static function findAll(): array
    {
        $pdo = self::db();
        return $pdo->query("
            SELECT a.*, c.nom as categorie_nom,
                   (SELECT COUNT(*) FROM images WHERE article_id = a.id) as nb_images
            FROM articles a
            LEFT JOIN categories c ON a.categorie_id = c.id
            ORDER BY a.date_creation DESC
        ")->fetchAll();
    }

    /**
     * Récupère les derniers articles
     */
    public static function findRecent(int $limit = 5): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            SELECT a.*, c.nom as categorie_nom
            FROM articles a
            LEFT JOIN categories c ON a.categorie_id = c.id
            ORDER BY a.date_creation DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un article par son ID avec catégorie
     */
    public static function findById(int $id): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            SELECT a.*, c.nom as categorie_nom, c.slug as categorie_slug
            FROM articles a
            LEFT JOIN categories c ON a.categorie_id = c.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Récupère un article par son slug avec catégorie
     */
    public static function findBySlug(string $slug): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            SELECT a.*, c.nom as categorie_nom, c.slug as categorie_slug
            FROM articles a
            LEFT JOIN categories c ON a.categorie_id = c.id
            WHERE a.slug = ?
        ");
        $stmt->execute([$slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Récupère les images d'un article
     */
    public static function findImages(int $articleId): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("SELECT * FROM images WHERE article_id = ? ORDER BY id ASC");
        $stmt->execute([$articleId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère les articles par catégorie
     */
    public static function findByCategory(int $categoryId, int $limit = 100): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            SELECT a.*, c.nom as categorie_nom, c.slug as categorie_slug,
                   (SELECT COUNT(*) FROM images WHERE article_id = a.id) as nb_images
            FROM articles a
            LEFT JOIN categories c ON a.categorie_id = c.id
            WHERE a.categorie_id = ?
            ORDER BY a.date_creation DESC
            LIMIT ?
        ");
        $stmt->execute([$categoryId, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère les articles connexes (même catégorie, différent ID)
     */
    public static function findRelated(int $articleId, int $categoryId, int $limit = 3): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            SELECT a.*, c.nom as categorie_nom, c.slug as categorie_slug
            FROM articles a
            LEFT JOIN categories c ON a.categorie_id = c.id
            WHERE a.categorie_id = ? AND a.id != ?
            ORDER BY a.date_creation DESC
            LIMIT ?
        ");
        $stmt->execute([$categoryId, $articleId, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Crée un nouvel article
     */
    public static function create(array $data): int
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            INSERT INTO articles (titre, slug, contenu, extrait, image, image_alt, meta_titre, meta_description, categorie_id, date_publication)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['titre'],
            $data['slug'],
            $data['contenu'],
            $data['extrait'],
            $data['image'],
            $data['image_alt'],
            $data['meta_titre'],
            $data['meta_description'],
            $data['categorie_id'],
            $data['date_publication']
        ]);
        return (int) $pdo->lastInsertId();
    }

    /**
     * Met à jour un article
     */
    public static function update(int $id, array $data): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("
            UPDATE articles SET
                titre = ?, slug = ?, contenu = ?, extrait = ?,
                image = ?, image_alt = ?, meta_titre = ?, meta_description = ?,
                categorie_id = ?, date_publication = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['titre'],
            $data['slug'],
            $data['contenu'],
            $data['extrait'],
            $data['image'],
            $data['image_alt'],
            $data['meta_titre'],
            $data['meta_description'],
            $data['categorie_id'],
            $data['date_publication'],
            $id
        ]);
    }

    /**
     * Supprime un article
     */
    public static function delete(int $id): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$id]);
    }

    /**
     * Compte le nombre d'articles
     */
    public static function count(): int
    {
        $pdo = self::db();
        return (int) $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
    }
}
