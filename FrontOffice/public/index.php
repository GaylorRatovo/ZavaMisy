<?php
/**
 * Page d'accueil du FrontOffice
 * URL: /
 */

require_once __DIR__ . '/../includes/functions.php';

// SEO Meta tags
$pageTitle = 'Accueil';
$metaTitle = 'Iran Conflit - Analyse et actualités sur les crises au Moyen-Orient';
$metaDescription = 'Votre source d\'information et d\'analyse sur les conflits et les enjeux géopolitiques au Moyen-Orient. Articles, analyses et reportages.';

require_once __DIR__ . '/../includes/header.php';

// Récupérer les derniers articles depuis l'API
$articles = getArticles();
$latestArticles = array_slice($articles, 0, 6);
?>

<!-- Hero Section -->
<section class="hero">
    <h2 class="hero__title">Bienvenue sur Iran Conflit</h2>
    <p class="hero__description">
        Votre source d'information et d'analyse sur les conflits et les enjeux géopolitiques au Moyen-Orient
    </p>
</section>

<!-- Latest Articles -->
<h2 class="section-title">Derniers Articles</h2>

<?php if (empty($articles)): ?>
    <div class="alert alert-info">
        Aucun article publié pour le moment.
    </div>
<?php else: ?>
    <div class="articles-grid">
        <?php foreach ($latestArticles as $article): ?>
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

                <h3 class="article-card__title" itemprop="headline">
                    <a href="<?= articleUrl($article) ?>"><?= e($article['titre']) ?></a>
                </h3>

                <?php if (!empty($article['extrait'])): ?>
                <p class="article-card__excerpt" itemprop="description">
                    <?= e(truncate($article['extrait'], 120)) ?>
                </p>
                <?php elseif (!empty($article['contenu'])): ?>
                <p class="article-card__excerpt" itemprop="description">
                    <?= e(truncate($article['contenu'], 120)) ?>
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

    <?php if (count($articles) > 6): ?>
    <div class="text-center mt-4">
        <a href="/articles" class="btn btn-primary">Voir tous les articles</a>
    </div>
    <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
