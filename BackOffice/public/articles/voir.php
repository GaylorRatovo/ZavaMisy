<?php
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/Categorie.php';
require_once __DIR__ . '/../../models/Image.php';
require_once __DIR__ . '/../../includes/functions.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    redirect('/articles/');
}

$article = Article::findById((int) $id);
if (!$article) {
    redirect('/articles/');
}

$images = Image::findByArticleId((int) $id);

// Récupérer la catégorie
$categorie = null;
if ($article['categorie_id']) {
    $categorie = Categorie::findById((int) $article['categorie_id']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($article['meta_description'] ?? $article['extrait']) ?>">
    <title><?= e($article['meta_titre'] ?: $article['titre']) ?> - Iran Conflit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Georgia, 'Times New Roman', serif;
            background-color: #f8f8f8;
            color: #222;
            line-height: 1.8;
        }

        /* Barre de prévisualisation */
        .preview-bar {
            background: #1a1a2e;
            color: #fff;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .preview-bar span {
            font-size: 0.9rem;
            color: #e94560;
            font-weight: 600;
        }

        .preview-bar a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: #e94560;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .preview-bar a:hover {
            background: #c73e54;
        }

        /* Article */
        article {
            max-width: 800px;
            margin: 0 auto;
            padding: 3rem 2rem;
            background: #fff;
            min-height: calc(100vh - 52px);
        }

        .category {
            display: inline-block;
            background: #e94560;
            color: #fff;
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        h1 {
            font-size: 2.5rem;
            line-height: 1.2;
            margin-bottom: 1rem;
            color: #1a1a2e;
        }

        .meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .excerpt {
            font-size: 1.25rem;
            color: #444;
            font-style: italic;
            margin-bottom: 2rem;
            padding-left: 1.5rem;
            border-left: 4px solid #e94560;
        }

        .featured-image {
            width: 100%;
            margin-bottom: 2rem;
        }

        .featured-image img {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .featured-image figcaption {
            font-size: 0.85rem;
            color: #666;
            text-align: center;
            margin-top: 0.5rem;
            font-style: italic;
        }

        .content {
            font-size: 1.1rem;
        }

        .content p {
            margin-bottom: 1.5rem;
        }

        .content h2 {
            font-size: 1.75rem;
            margin: 2rem 0 1rem;
            color: #1a1a2e;
        }

        .content h3 {
            font-size: 1.4rem;
            margin: 1.5rem 0 1rem;
            color: #1a1a2e;
        }

        /* Galerie d'images */
        .gallery {
            margin: 2rem 0;
        }

        .gallery h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #1a1a2e;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .gallery-grid figure {
            margin: 0;
        }

        .gallery-grid img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .gallery-grid figcaption {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }

        /* SEO Info */
        .seo-info {
            margin-top: 3rem;
            padding: 1.5rem;
            background: #f0f0f0;
            border-radius: 4px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .seo-info h4 {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .seo-info dl {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .seo-info dt {
            font-weight: 600;
            color: #444;
        }

        .seo-info dd {
            color: #666;
        }

        .seo-info code {
            background: #fff;
            padding: 0.1rem 0.3rem;
            border-radius: 2px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="preview-bar">
        <span>Mode previsualisation</span>
        <div>
            <a href="/articles/modifier.php?id=<?= $id ?>">Modifier</a>
            <a href="/articles/" style="background: #16213e; margin-left: 0.5rem;">Retour a la liste</a>
        </div>
    </div>

    <article>
        <?php if ($categorie): ?>
            <span class="category"><?= e($categorie['nom']) ?></span>
        <?php endif; ?>

        <h1><?= e($article['titre']) ?></h1>

        <div class="meta">
            <?php if ($article['date_publication']): ?>
                Publie le <?= date('d F Y', strtotime($article['date_publication'])) ?>
            <?php else: ?>
                <em>Non publie</em>
            <?php endif; ?>
        </div>

        <?php if ($article['extrait']): ?>
            <p class="excerpt"><?= e($article['extrait']) ?></p>
        <?php endif; ?>

        <?php if ($article['image']): ?>
            <figure class="featured-image">
                <img src="/uploads/<?= e($article['image']) ?>" alt="<?= e($article['image_alt']) ?>">
                <?php if ($article['image_alt']): ?>
                    <figcaption><?= e($article['image_alt']) ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endif; ?>

        <div class="content">
            <?= nl2br(e($article['contenu'])) ?>
        </div>

        <?php if (!empty($images)): ?>
            <div class="gallery">
                <h3>Images supplementaires</h3>
                <div class="gallery-grid">
                    <?php foreach ($images as $img): ?>
                        <figure>
                            <img src="/uploads/<?= e($img['fichier']) ?>" alt="<?= e($img['alt']) ?>">
                            <figcaption><?= e($img['alt']) ?></figcaption>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="seo-info">
            <h4>Informations SEO</h4>
            <dl>
                <dt>URL (slug)</dt>
                <dd><code>/articles/<?= e($article['slug']) ?></code></dd>

                <dt>Meta titre</dt>
                <dd><?= e($article['meta_titre'] ?: '-') ?></dd>

                <dt>Meta description</dt>
                <dd><?= e($article['meta_description'] ?: '-') ?></dd>

                <dt>Alt image</dt>
                <dd><?= e($article['image_alt'] ?: '-') ?></dd>
            </dl>
        </div>
    </article>
</body>
</html>
