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
    <link rel="stylesheet" href="/css/article.css">
</head>
<body>
    <div class="preview-bar">
        <span>Mode previsualisation</span>
        <div>
            <a href="/articles/modifier.php?id=<?= $id ?>">Modifier</a>
            <a href="/articles/" class="secondary">Retour a la liste</a>
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
