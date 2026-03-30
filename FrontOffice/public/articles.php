<?php
/**
 * Page liste des articles avec filtres par catégorie
 * URL: /articles ou /categorie/{slug}
 */

require_once __DIR__ . '/../includes/functions.php';

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 6;

// Filtrage par catégorie
$currentCategory = $_GET['categorie'] ?? null;
$categoryData = null;

if ($currentCategory) {
    $categoryData = getCategoryBySlug($currentCategory);
}

// Récupérer les articles
if ($categoryData) {
    $allArticles = getArticlesByCategory((int)$categoryData['id']);
} else {
    $allArticles = getArticles();
}

// Pagination
$totalArticles = count($allArticles);
$totalPages = ceil($totalArticles / $perPage);
$offset = ($page - 1) * $perPage;
$articles = array_slice($allArticles, $offset, $perPage);

// Récupérer les catégories pour les filtres
$categories = getCategories();

// SEO Meta tags
if ($categoryData) {
    $pageTitle = $categoryData['nom'];
    $metaTitle = $categoryData['nom'] . ' - Articles - Iran Conflit';
    $metaDescription = 'Tous les articles de la catégorie ' . $categoryData['nom'] . ' sur Iran Conflit.';
    $canonicalUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/categorie/' . $currentCategory;
} else {
    $pageTitle = 'Tous les articles';
    $metaTitle = 'Articles - Iran Conflit';
    $metaDescription = 'Retrouvez tous nos articles d\'analyse et d\'actualités sur les crises et conflits au Moyen-Orient.';
    $canonicalUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/articles';
}

if ($page > 1) {
    $metaTitle .= ' - Page ' . $page;
    $canonicalUrl .= '?page=' . $page;
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Breadcrumb -->
<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <ol class="breadcrumb__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="/" class="breadcrumb__link" itemprop="item"><span itemprop="name">Accueil</span></a>
            <meta itemprop="position" content="1">
        </li>
        <?php if ($categoryData): ?>
        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="/articles" class="breadcrumb__link" itemprop="item"><span itemprop="name">Articles</span></a>
            <meta itemprop="position" content="2">
        </li>
        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span class="breadcrumb__current" itemprop="name"><?= e($categoryData['nom']) ?></span>
            <meta itemprop="position" content="3">
        </li>
        <?php else: ?>
        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span class="breadcrumb__current" itemprop="name">Articles</span>
            <meta itemprop="position" content="2">
        </li>
        <?php endif; ?>
    </ol>
</nav>

<!-- Page Title -->
<h1 class="section-title"><?= e($pageTitle) ?></h1>

<!-- Category Filters -->
<div class="filters" role="navigation" aria-label="Filtrer par catégorie">
    <a href="/articles" class="filter-btn <?= !$currentCategory ? 'filter-btn--active' : '' ?>">
        Tous
    </a>
    <?php foreach ($categories as $cat): ?>
    <a href="/categorie/<?= e($cat['slug']) ?>"
       class="filter-btn <?= $currentCategory === $cat['slug'] ? 'filter-btn--active' : '' ?>">
        <?= e($cat['nom']) ?>
        <?php if (isset($cat['nb_articles'])): ?>
        <span>(<?= (int)$cat['nb_articles'] ?>)</span>
        <?php endif; ?>
    </a>
    <?php endforeach; ?>
</div>

<?php if (empty($articles)): ?>
    <div class="alert alert-info">
        Aucun article trouvé<?= $categoryData ? ' dans cette catégorie' : '' ?>.
    </div>
<?php else: ?>
    <!-- Articles Grid -->
    <div class="articles-grid">
        <?php foreach ($articles as $article): ?>
        <article class="article-card" itemscope itemtype="https://schema.org/Article">
            <?php if (!empty($article['image'])): ?>
            <a href="<?= articleUrl($article) ?>" aria-label="Lire l'article : <?= e($article['titre']) ?>">
                <img src="<?= e(imageUrl($article['image'])) ?>"
                     alt="<?= e($article['image_alt'] ?? $article['titre']) ?>"
                     class="article-card__image"
                     loading="lazy"
                     itemprop="image">
            </a>
            <?php else: ?>
            <a href="<?= articleUrl($article) ?>" aria-label="Lire l'article : <?= e($article['titre']) ?>">
                <div class="article-card__placeholder">IC</div>
            </a>
            <?php endif; ?>

            <div class="article-card__content">
                <?php if (!empty($article['categorie_nom'])): ?>
                <p class="article-card__category">
                    <a href="/categorie/<?= e($article['categorie_slug'] ?? '') ?>" aria-label="Voir tous les articles de la catégorie <?= e($article['categorie_nom']) ?>">
                        <?= e($article['categorie_nom']) ?>
                    </a>
                </p>
                <?php endif; ?>

                <h2 class="article-card__title" itemprop="headline">
                    <a href="<?= articleUrl($article) ?>"><?= e($article['titre']) ?></a>
                </h2>

                <?php if (!empty($article['extrait'])): ?>
                <p class="article-card__excerpt" itemprop="description">
                    <?= e(truncate($article['extrait'], 120)) ?>
                </p>
                <?php endif; ?>

                <p class="article-card__meta">
                    <time datetime="<?= e($article['date_publication'] ?? $article['date_creation']) ?>" itemprop="datePublished">
                        <?= formatDate($article['date_publication'] ?? $article['date_creation']) ?>
                    </time>
                </p>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="pagination" aria-label="Pagination des articles">
        <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?><?= $currentCategory ? '&categorie=' . e($currentCategory) : '' ?>"
           class="pagination__link" rel="prev">
            &laquo; Précédent
        </a>
        <?php endif; ?>

        <?php
        // Pagination intelligente avec ellipsis
        $delta = 2; // Pages à afficher autour de la page courante
        $range = [];

        // Calculer les pages à afficher
        for ($i = 1; $i <= $totalPages; $i++) {
            // Toujours afficher: première page, dernière page, et pages autour de la page courante
            if ($i === 1 || $i === $totalPages || ($i >= $page - $delta && $i <= $page + $delta)) {
                $range[] = $i;
            }
        }

        $lastShown = 0;
        foreach ($range as $i):
            // Afficher ellipsis si écart > 1
            if ($lastShown && $i - $lastShown > 1): ?>
            <span class="pagination__ellipsis">...</span>
            <?php endif;
            $lastShown = $i;

            if ($i === $page): ?>
            <span class="pagination__link pagination__link--active"><?= $i ?></span>
            <?php else: ?>
            <a href="?page=<?= $i ?><?= $currentCategory ? '&categorie=' . e($currentCategory) : '' ?>"
               class="pagination__link">
                <?= $i ?>
            </a>
            <?php endif;
        endforeach; ?>

        <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?><?= $currentCategory ? '&categorie=' . e($currentCategory) : '' ?>"
           class="pagination__link" rel="next">
            Suivant &raquo;
        </a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
