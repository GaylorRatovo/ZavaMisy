<?php

/**
 * API Endpoint - Articles
 * GET /api/articles.php - Liste tous les articles
 * GET /api/articles.php?id=1 - Récupère un article par ID
 * GET /api/articles.php?slug=xxx - Récupère un article par slug
 * GET /api/articles.php?category=1 - Filtre par catégorie
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

require_once __DIR__ . '/../../models/Article.php';

try {
    // Récupération par ID
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $article = Article::findById((int)$_GET['id']);

        if (!$article) {
            http_response_code(404);
            echo json_encode(['error' => 'Article non trouvé']);
            exit;
        }

        // Ajouter les images
        $article['images'] = Article::findImages((int)$_GET['id']);

        // Ajouter les articles connexes
        if ($article['categorie_id']) {
            $article['related'] = Article::findRelated(
                (int)$_GET['id'],
                (int)$article['categorie_id'],
                3
            );
        } else {
            $article['related'] = [];
        }

        echo json_encode($article, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // Récupération par slug
    if (isset($_GET['slug']) && !empty($_GET['slug'])) {
        $article = Article::findBySlug($_GET['slug']);

        if (!$article) {
            http_response_code(404);
            echo json_encode(['error' => 'Article non trouvé']);
            exit;
        }

        // Ajouter les images
        $article['images'] = Article::findImages((int)$article['id']);

        // Ajouter les articles connexes
        if ($article['categorie_id']) {
            $article['related'] = Article::findRelated(
                (int)$article['id'],
                (int)$article['categorie_id'],
                3
            );
        } else {
            $article['related'] = [];
        }

        echo json_encode($article, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // Filtrage par catégorie
    if (isset($_GET['category']) && is_numeric($_GET['category'])) {
        $articles = Article::findByCategory((int)$_GET['category']);
        echo json_encode([
            'success' => true,
            'count' => count($articles),
            'data' => $articles
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // Liste complète
    $articles = Article::findAll();
    echo json_encode([
        'success' => true,
        'count' => count($articles),
        'data' => $articles
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur'
    ], JSON_UNESCAPED_UNICODE);
}
