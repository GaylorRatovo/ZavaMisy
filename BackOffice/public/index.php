<?php
$pageTitle = 'Tableau de bord';
require_once __DIR__ . '/../includes/header.php';

$pdo = getConnection();

// Statistiques
$nbArticles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$nbCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$nbImages = $pdo->query("SELECT COUNT(*) FROM images")->fetchColumn();

// Derniers articles
$derniersArticles = $pdo->query("
    SELECT a.*, c.nom as categorie_nom
    FROM articles a
    LEFT JOIN categories c ON a.categorie_id = c.id
    ORDER BY a.date_creation DESC
    LIMIT 5
")->fetchAll();
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
        <p>Images</p>
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
