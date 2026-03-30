<?php

/**
 * Configuration et fonctions API pour le FrontOffice
 */

/**
 * Retourne l'URL de base de l'API (interne Docker)
 * Utilisée pour les appels API serveur-à-serveur
 */
function getApiUrl(): string
{
    return getenv('API_URL') ?: 'http://back-office';
}

/**
 * Retourne l'URL publique pour les uploads (accessible par le navigateur)
 * Cette URL doit être accessible depuis le navigateur de l'utilisateur
 */
function getUploadsUrl(): string
{
    return getenv('UPLOADS_URL') ?: 'http://localhost:8001';
}

/**
 * Effectue une requête GET vers l'API
 */
function apiGet(string $endpoint): array
{
    $url = getApiUrl() . $endpoint;

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Accept: application/json',
            'timeout' => 10,
            'ignore_errors' => true
        ]
    ]);

    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        return ['error' => true, 'message' => 'Impossible de contacter l\'API'];
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => true, 'message' => 'Réponse API invalide'];
    }

    return $data;
}

/**
 * Récupère tous les articles
 */
function getArticles(): array
{
    $result = apiGet('/api/articles.php');
    return $result['data'] ?? [];
}

/**
 * Récupère un article par son ID
 */
function getArticleById(int $id): ?array
{
    $result = apiGet('/api/articles.php?id=' . $id);
    return isset($result['error']) ? null : $result;
}

/**
 * Récupère un article par son slug
 */
function getArticleBySlug(string $slug): ?array
{
    $slug = urlencode($slug);
    $result = apiGet('/api/articles.php?slug=' . $slug);
    return isset($result['error']) ? null : $result;
}

/**
 * Récupère les articles par catégorie (par ID)
 */
function getArticlesByCategory(int $categoryId): array
{
    $result = apiGet('/api/articles.php?category=' . $categoryId);
    return $result['data'] ?? [];
}

/**
 * Récupère toutes les catégories
 */
function getCategories(): array
{
    $result = apiGet('/api/categories.php');
    return $result['data'] ?? [];
}

/**
 * Récupère une catégorie par son slug
 */
function getCategoryBySlug(string $slug): ?array
{
    $categories = getCategories();
    foreach ($categories as $cat) {
        if ($cat['slug'] === $slug) {
            return $cat;
        }
    }
    return null;
}
