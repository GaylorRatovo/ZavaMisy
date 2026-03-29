<?php
require_once __DIR__ . '/../../models/Article.php';

$pageTitle = 'Articles';
require_once __DIR__ . '/../../includes/header.php';

$articles = Article::findAll();
?>

<div class="card">
    <div class="card-header">
        <h2>Gestion des articles</h2>
        <a href="/articles/ajouter.php" class="btn btn-primary">Nouvel article</a>
    </div>

    <?php if (empty($articles)): ?>
        <p class="text-muted text-center">Aucun article.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Categorie</th>
                    <th>Images</th>
                    <th>Date publication</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td>
                            <?php if ($article['image']): ?>
                                <img src="/uploads/<?= e($article['image']) ?>" alt="<?= e($article['image_alt']) ?>" class="img-preview">
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= e($article['titre']) ?></strong>
                            <br><small class="text-muted"><?= e(truncate($article['extrait'] ?? '', 50)) ?></small>
                        </td>
                        <td><?= e($article['categorie_nom'] ?? '-') ?></td>
                        <td><?= $article['nb_images'] ?></td>
                        <td><?= formatDate($article['date_publication']) ?></td>
                        <td class="actions">
                            <a href="/articles/modifier.php?id=<?= $article['id'] ?>" class="btn btn-secondary btn-sm">Modifier</a>
                            <a href="/articles/traitement.php?action=supprimer&id=<?= $article['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
