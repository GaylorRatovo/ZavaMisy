<?php
session_start();
require_once __DIR__ . '/functions.php';

// Protéger toutes les pages du BackOffice
requireAuth();

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'BackOffice') ?> - Iran Conflit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header class="header">
        <h1>Iran <span>Conflit</span></h1>
        <div class="user-info">
            <span><?= e($_SESSION['admin_username'] ?? '') ?></span>
            <a href="/logout.php" class="btn-logout">Deconnexion</a>
        </div>
    </header>

    <nav class="nav">
        <ul>
            <li><a href="/index.php" class="<?= $currentPage === 'index' && $currentDir === 'public' ? 'active' : '' ?>">Tableau de bord</a></li>
            <li><a href="/articles/" class="<?= $currentDir === 'articles' ? 'active' : '' ?>">Articles</a></li>
            <li><a href="/categories/" class="<?= $currentDir === 'categories' ? 'active' : '' ?>">Categories</a></li>
        </ul>
    </nav>

    <main class="main">
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
