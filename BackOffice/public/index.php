<?php
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../models/Image.php';

$pageTitle = 'Tableau de bord';
require_once __DIR__ . '/../includes/header.php';

// Statistiques via les modèles
$nbArticles = Article::count();
$nbCategories = Categorie::count();
$nbImages = Image::count();

// Derniers articles
$derniersArticles = Article::findRecent(5);
?>

<h2 style="margin-bottom: 1.5rem; color: #1a1a2e;">Tableau de bord</h2>

<div class="stats">
    <div class="stat-card">
        <h3><?= $nbArticles ?></h3>
        <p>Articles</p>
    </div>
    <div class="stat-card">
        <h3><?= $nbCategories ?></h3>
        <p>Categories</p>
    </div>
    <div class="stat-card">
        <h3><?= $nbImages ?></h3>
        <p>Images (dans les articles)</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Derniers articles</h2>
        <a href="/articles/ajouter.php" class="btn btn-primary">Nouvel article</a>
    </div>

    <?php if (empty($derniersArticles)): ?>
        <p class="text-muted text-center">Aucun article pour le moment.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Categorie</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($derniersArticles as $article): ?>
                    <tr>
                        <td><?= e($article['titre']) ?></td>
                        <td><?= e($article['categorie_nom'] ?? '-') ?></td>
                        <td><?= formatDate($article['date_creation']) ?></td>
                        <td class="actions">
                            <a href="/articles/modifier.php?id=<?= $article['id'] ?>" class="btn btn-secondary btn-sm">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
