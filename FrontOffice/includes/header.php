<?php
/**
 * Header - FrontOffice Iran Conflit
 * Optimisé SEO avec meta tags dynamiques
 */
session_start();
require_once __DIR__ . '/../config/api.php';
require_once __DIR__ . '/functions.php';

$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// Variables SEO par défaut
$siteName = 'Iran Conflit';
$siteDescription = 'Analyse et actualités sur les crises et conflits au Moyen-Orient';
$siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$currentUrl = $siteUrl . $_SERVER['REQUEST_URI'];

// Meta tags (peuvent être surchargés par les pages)
$pageTitle = $pageTitle ?? 'Accueil';
$metaTitle = $metaTitle ?? $pageTitle . ' - ' . $siteName;
$metaDescription = $metaDescription ?? $siteDescription;
$metaImage = $metaImage ?? $siteUrl . '/assets/images/og-default.jpg';
$canonicalUrl = $canonicalUrl ?? $currentUrl;

// Récupérer les catégories pour la navigation
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <title><?= e($metaTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= isset($article) ? 'article' : 'website' ?>">
    <meta property="og:url" content="<?= e($currentUrl) ?>">
    <meta property="og:title" content="<?= e($metaTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:image" content="<?= e($metaImage) ?>">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta property="og:locale" content="fr_FR">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($metaTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= e($metaImage) ?>">

    <!-- Article specific meta (si article) -->
    <?php if (isset($article) && isset($article['date_publication'])): ?>
    <meta property="article:published_time" content="<?= e($article['date_publication']) ?>">
    <?php if (isset($article['categorie_nom'])): ?>
    <meta property="article:section" content="<?= e($article['categorie_nom']) ?>">
    <?php endif; ?>
    <?php endif; ?>

    <!-- Preconnect pour performances -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Google Fonts avec display=swap pour éviter FOIT -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet"></noscript>

    <!-- Styles critiques -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Swiper CSS (chargé uniquement si nécessaire) -->
    <?php if (isset($hasMultipleImages) && $hasMultipleImages): ?>
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <header class="header">
        <div class="header__container">
            <?php if ($currentPage === 'index'): ?>
            <h1 class="header__title">
                <a href="/">Iran <span>Conflit</span></a>
            </h1>
            <?php else: ?>
            <p class="header__title">
                <a href="/">Iran <span>Conflit</span></a>
            </p>
            <?php endif; ?>
            <p class="header__tagline">Analyse et actualités sur les crises au Moyen-Orient</p>
        </div>
    </header>

    <nav class="nav" aria-label="Navigation principale">
        <div class="nav__container">
            <ul class="nav__list">
                <li><a href="/" class="nav__link <?= $currentPage === 'index' ? 'nav__link--active' : '' ?>">Accueil</a></li>
                <li><a href="/articles" class="nav__link <?= ($currentPage === 'articles' && empty($currentCategory)) ? 'nav__link--active' : '' ?>">Articles</a></li>
                <?php if (!empty($categories) && !isset($categories['error'])): ?>
                    <?php foreach (array_slice($categories, 0, 4) as $cat): ?>
                    <li>
                        <a href="/categorie/<?= e($cat['slug']) ?>"
                           class="nav__link <?= (!empty($currentCategory) && $currentCategory === $cat['slug']) ? 'nav__link--active' : '' ?>">
                            <?= e($cat['nom']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="main">
