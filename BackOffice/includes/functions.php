<?php

/**
 * Fonctions utilitaires pour le BackOffice
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Génère un slug à partir d'un titre
 */
function slugify(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'n-a';
}

/**
 * Échappe les caractères HTML
 */
function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirige vers une URL
 */
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

/**
 * Affiche un message flash
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Tronque un texte
 */
function truncate(string $text, int $length = 100): string
{
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Formate une date
 */
function formatDate(?string $date): string
{
    if (!$date) return '-';
    return date('d/m/Y H:i', strtotime($date));
}
