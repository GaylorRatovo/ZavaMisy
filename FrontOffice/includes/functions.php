<?php

/**
 * Fonctions utilitaires pour le FrontOffice
 */

// Inclure le fichier API pour les fonctions de communication avec le backend
require_once __DIR__ . '/../config/api.php';

/**
 * Échappe les caractères HTML
 */
function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Tronque un texte
 */
function truncate(string $text, int $length = 150): string
{
    $text = strip_tags($text);
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Formate une date en français
 */
function formatDate(?string $date): string
{
    if (!$date) return '';

    $timestamp = strtotime($date);
    $mois = [
        1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril',
        5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août',
        9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'
    ];

    $jour = date('j', $timestamp);
    $moisNum = (int)date('n', $timestamp);
    $annee = date('Y', $timestamp);

    return "$jour {$mois[$moisNum]} $annee";
}

/**
 * Génère l'URL d'une image depuis le BackOffice
 * Utilise l'URL publique accessible par le navigateur
 */
function imageUrl(?string $path): string
{
    if (!$path) return '';
    // Si le chemin commence déjà par http, le retourner tel quel
    if (str_starts_with($path, 'http')) {
        return $path;
    }
    $uploadsUrl = getUploadsUrl();
    return $uploadsUrl . '/uploads/' . ltrim($path, '/');
}

/**
 * Génère l'URL d'un article (SEO-friendly)
 */
function articleUrl(array $article): string
{
    return '/article/' . ($article['slug'] ?? $article['id']);
}

/**
 * Génère l'URL d'une catégorie (SEO-friendly)
 */
function categoryUrl(array $category): string
{
    return '/categorie/' . ($category['slug'] ?? $category['id']);
}

/**
 * Génère un slug à partir d'un texte
 */
function slugify(string $text): string
{
    $text = strtolower($text);
    $text = preg_replace('/[éèêë]/u', 'e', $text);
    $text = preg_replace('/[àâä]/u', 'a', $text);
    $text = preg_replace('/[ùûü]/u', 'u', $text);
    $text = preg_replace('/[îï]/u', 'i', $text);
    $text = preg_replace('/[ôö]/u', 'o', $text);
    $text = preg_replace('/[ç]/u', 'c', $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Retourne le temps de lecture estimé
 */
function readingTime(string $content): int
{
    $wordCount = str_word_count(strip_tags($content));
    return max(1, ceil($wordCount / 200)); // 200 mots par minute
}

/**
 * Traite le contenu HTML pour corriger les URLs des images
 * Les images dans TinyMCE utilisent /uploads/... qui doit pointer vers le BackOffice public
 */
function processContent(string $content): string
{
    $uploadsUrl = getUploadsUrl();

    // Remplacer les URLs relatives des images par des URLs absolues vers le BackOffice
    // Pattern: src="/uploads/..." ou src='/uploads/...'
    $content = preg_replace(
        '/src=["\']\/uploads\/([^"\']+)["\']/i',
        'src="' . $uploadsUrl . '/uploads/$1"',
        $content
    );

    // Aussi gérer les URLs sans le slash initial
    $content = preg_replace(
        '/src=["\']uploads\/([^"\']+)["\']/i',
        'src="' . $uploadsUrl . '/uploads/$1"',
        $content
    );

    return $content;
}
