<?php
/**
 * Page détail d'un article
 * URL: /article/{slug}
 */

require_once __DIR__ . '/../includes/functions.php';

// Récupérer le slug depuis l'URL
$slug = $_GET['slug'] ?? null;

if (!$slug) {
    http_response_code(404);
    $pageTitle = 'Article non trouvé';
    $metaTitle = '404 - Article non trouvé - Iran Conflit';
    $metaDescription = 'L\'article demandé n\'existe pas.';
    require_once __DIR__ . '/../includes/header.php';
    echo '<div class="alert alert-error">Article non trouvé.</div>';
    echo '<a href="/articles" class="btn btn-primary">Retour aux articles</a>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Récupérer l'article
$article = getArticleBySlug($slug);

if (!$article) {
    http_response_code(404);
    $pageTitle = 'Article non trouvé';
    $metaTitle = '404 - Article non trouvé - Iran Conflit';
    $metaDescription = 'L\'article demandé n\'existe pas.';
    require_once __DIR__ . '/../includes/header.php';
    echo '<div class="alert alert-error">Article non trouvé.</div>';
    echo '<a href="/articles" class="btn btn-primary">Retour aux articles</a>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Images de l'article
$images = $article['images'] ?? [];
$hasMultipleImages = count($images) > 1;

// Articles connexes
$relatedArticles = $article['related'] ?? [];

// SEO Meta tags
$pageTitle = $article['titre'];
$metaTitle = ($article['meta_titre'] ?: $article['titre']) . ' - Iran Conflit';
$metaDescription = $article['meta_description'] ?: truncate($article['extrait'] ?? $article['contenu'], 160);
$canonicalUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/article/' . $article['slug'];

// Image pour Open Graph
if (!empty($article['image'])) {
    $metaImage = imageUrl($article['image']);
} elseif (!empty($images)) {
    $metaImage = imageUrl($images[0]['fichier']);
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Breadcrumb avec Schema.org -->
<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <ol class="breadcrumb__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="/" class="breadcrumb__link" itemprop="item"><span itemprop="name">Accueil</span></a>
            <meta itemprop="position" content="1">
        </li>
        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="/articles" class="breadcrumb__link" itemprop="item"><span itemprop="name">Articles</span></a>
            <meta itemprop="position" content="2">
        </li>
        <?php if (!empty($article['categorie_nom'])): ?>
        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="/categorie/<?= e($article['categorie_slug']) ?>" class="breadcrumb__link" itemprop="item">
                <span itemprop="name"><?= e($article['categorie_nom']) ?></span>
            </a>
            <meta itemprop="position" content="3">
        </li>
        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span class="breadcrumb__current" itemprop="name"><?= e(truncate($article['titre'], 50)) ?></span>
            <meta itemprop="position" content="4">
        </li>
        <?php else: ?>
        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span class="breadcrumb__current" itemprop="name"><?= e(truncate($article['titre'], 50)) ?></span>
            <meta itemprop="position" content="3">
        </li>
        <?php endif; ?>
    </ol>
</nav>

<!-- Article -->
<article class="article-single" itemscope itemtype="https://schema.org/NewsArticle">
    <!-- Header -->
    <header class="article-single__header">
        <?php if (!empty($article['categorie_nom'])): ?>
        <p class="article-single__category">
            <a href="/categorie/<?= e($article['categorie_slug']) ?>"
               itemprop="articleSection"
               aria-label="Voir tous les articles de la catégorie <?= e($article['categorie_nom']) ?>">
                <?= e($article['categorie_nom']) ?>
            </a>
        </p>
        <?php endif; ?>

        <h1 class="article-single__title" itemprop="headline"><?= e($article['titre']) ?></h1>

        <p class="article-single__meta">
            Publié le
            <time datetime="<?= e($article['date_publication'] ?? $article['date_creation']) ?>" itemprop="datePublished">
                <?= formatDate($article['date_publication'] ?? $article['date_creation']) ?>
            </time>
            <?php if (!empty($article['contenu'])): ?>
            &bull; <?= readingTime($article['contenu']) ?> min de lecture
            <?php endif; ?>
        </p>
    </header>

    <!-- Images Carousel -->
    <?php if ($hasMultipleImages): ?>
    <div class="article-carousel">
        <div class="swiper">
            <div class="swiper-wrapper">
                <?php foreach ($images as $index => $image): ?>
                <div class="swiper-slide">
                    <img src="<?= e(imageUrl($image['fichier'])) ?>"
                         alt="<?= e($image['alt'] ?? $article['titre'] . ' - Image ' . ($index + 1)) ?>"
                         itemprop="image"
                         loading="<?= $index === 0 ? 'eager' : 'lazy' ?>">
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    <?php elseif (!empty($images)): ?>
    <img src="<?= e(imageUrl($images[0]['fichier'])) ?>"
         alt="<?= e($images[0]['alt'] ?? $article['titre']) ?>"
         class="article-single__image"
         itemprop="image">
    <?php elseif (!empty($article['image'])): ?>
    <img src="<?= e(imageUrl($article['image'])) ?>"
         alt="<?= e($article['image_alt'] ?? $article['titre']) ?>"
         class="article-single__image"
         itemprop="image">
    <?php endif; ?>

    <!-- Content -->
    <div class="article-content" itemprop="articleBody">
        <?= processContent($article['contenu'] ?? '') ?>
    </div>

    <!-- Schema.org metadata -->
    <meta itemprop="author" content="Iran Conflit">
    <meta itemprop="publisher" content="Iran Conflit">
    <?php if (!empty($article['date_modification'])): ?>
    <meta itemprop="dateModified" content="<?= e($article['date_modification']) ?>">
    <?php endif; ?>
</article>

<!-- Related Articles -->
<?php if (!empty($relatedArticles)): ?>
<section class="related-articles">
    <h2 class="related-articles__title">Articles connexes</h2>
    <div class="related-articles__grid">
        <?php foreach ($relatedArticles as $related): ?>
        <article class="article-card">
            <?php if (!empty($related['image'])): ?>
            <a href="<?= articleUrl($related) ?>" aria-label="Lire l'article connexe : <?= e($related['titre']) ?>">
                <img src="<?= e(imageUrl($related['image'])) ?>"
                     alt="<?= e($related['image_alt'] ?? $related['titre']) ?>"
                     class="article-card__image"
                     loading="lazy">
            </a>
            <?php else: ?>
            <a href="<?= articleUrl($related) ?>" aria-label="Lire l'article connexe : <?= e($related['titre']) ?>">
                <div class="article-card__placeholder">IC</div>
            </a>
            <?php endif; ?>

            <div class="article-card__content">
                <h3 class="article-card__title">
                    <a href="<?= articleUrl($related) ?>"><?= e($related['titre']) ?></a>
                </h3>
                <p class="article-card__meta">
                    <?= formatDate($related['date_publication'] ?? $related['date_creation']) ?>
                </p>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Back Link -->
<a href="/articles" class="back-link mt-4">Retour aux articles</a>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
