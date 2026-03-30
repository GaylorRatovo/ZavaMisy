<?php

/**
 * API Endpoint - Liste des categories
 * GET /api/categories.php
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

require_once __DIR__ . '/../../models/Categorie.php';

try {
    $categories = Categorie::findAll();

    echo json_encode([
        'success' => true,
        'count' => count($categories),
        'data' => $categories
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur'
    ], JSON_UNESCAPED_UNICODE);
}
