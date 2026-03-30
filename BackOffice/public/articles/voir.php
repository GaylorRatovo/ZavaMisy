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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-primary: #1a2f4e;
            --color-primary-light: #2a4a6e;
            --color-accent: #c9a962;
            --color-accent-dark: #b08d4a;
            --color-bg: #faf9f6;
            --color-white: #ffffff;
            --color-text: #2d2d2d;
            --color-text-muted: #6b6b6b;
            --color-border: #e5e2db;
            --font-heading: 'Cormorant Garamond', Georgia, 'Times New Roman', serif;
            --font-body: 'Libre Baskerville', Georgia, 'Times New Roman', serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--color-bg);
            color: var(--color-text);
            line-height: 1.8;
        }

        /* Barre de previsualisation */
        .preview-bar {
            background: var(--color-primary);
            color: var(--color-white);
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 3px solid var(--color-accent);
        }

        .preview-bar span {
            font-size: 0.9rem;
            color: var(--color-accent);
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .preview-bar a {
            color: var(--color-primary);
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: var(--color-accent);
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .preview-bar a:hover {
            background: var(--color-accent-dark);
        }

        .preview-bar a.secondary {
            background: transparent;
            border: 1px solid var(--color-accent);
            color: var(--color-accent);
        }

        .preview-bar a.secondary:hover {
            background: var(--color-accent);
            color: var(--color-primary);
        }

        /* Article */
        article {
            max-width: 800px;
            margin: 0 auto;
            padding: 3rem 2rem;
            background: var(--color-white);
            min-height: calc(100vh - 55px);
        }

        .category {
            display: inline-block;
            background: var(--color-accent);
            color: var(--color-primary);
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            font-weight: 700;
            border-radius: 3px;
        }

        h1 {
            font-family: var(--font-heading);
            font-size: 2.5rem;
            line-height: 1.2;
            margin-bottom: 1rem;
            color: var(--color-primary);
            font-weight: 600;
        }

        .meta {
            color: var(--color-text-muted);
            font-size: 0.9rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--color-border);
        }

        .excerpt {
            font-size: 1.25rem;
            color: var(--color-text-muted);
            font-style: italic;
            margin-bottom: 2rem;
            padding-left: 1.5rem;
            border-left: 4px solid var(--color-accent);
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
            color: var(--color-text-muted);
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
            font-family: var(--font-heading);
            font-size: 1.75rem;
            margin: 2rem 0 1rem;
            color: var(--color-primary);
        }

        .content h3 {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            margin: 1.5rem 0 1rem;
            color: var(--color-primary);
        }

        /* Images dans le contenu - IMPORTANT pour les images TinyMCE */
        .content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 1.5rem auto;
            border-radius: 4px;
        }

        .content figure {
            max-width: 100%;
            margin: 1.5rem 0;
        }

        .content figure img {
            width: 100%;
        }

        .content figcaption {
            font-size: 0.85rem;
            color: var(--color-text-muted);
            text-align: center;
            margin-top: 0.5rem;
            font-style: italic;
        }

        /* Galerie d'images */
        .gallery {
            margin: 2rem 0;
        }

        .gallery h3 {
            font-family: var(--font-heading);
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: var(--color-primary);
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
            color: var(--color-text-muted);
            margin-top: 0.25rem;
        }

        /* SEO Info */
        .seo-info {
            margin-top: 3rem;
            padding: 1.5rem;
            background: var(--color-bg);
            border-radius: 4px;
            border: 1px solid var(--color-border);
        }

        .seo-info h4 {
            font-family: var(--font-heading);
            font-size: 1rem;
            color: var(--color-primary);
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
            font-weight: 700;
            color: var(--color-primary);
        }

        .seo-info dd {
            color: var(--color-text-muted);
        }

        .seo-info code {
            background: var(--color-white);
            padding: 0.1rem 0.3rem;
            border-radius: 2px;
            font-size: 0.85rem;
            border: 1px solid var(--color-border);
        }

        @media (max-width: 600px) {
            article {
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 1.75rem;
            }

            .seo-info dl {
                grid-template-columns: 1fr;
            }

            .seo-info dt {
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="preview-bar">
        <span>Mode previsualisation</span>
        <div>
            <a href="/articles/modifier.php?id=<?= $id ?>">Modifier</a>
            <a href="/articles/" class="secondary" style="margin-left: 0.5rem;">Retour a la liste</a>
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
            <?= $article['contenu'] ?>
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
