<?php
$pageTitle = 'Articles';
require_once __DIR__ . '/../../includes/header.php';

$pdo = getConnection();

// Suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    // Supprimer l'image principale si elle existe
    $stmt = $pdo->prepare("SELECT image FROM articles WHERE id = ?");
    $stmt->execute([$_GET['supprimer']]);
    $article = $stmt->fetch();
    if ($article && $article['image']) {
        $imagePath = __DIR__ . '/../uploads/' . $article['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$_GET['supprimer']]);
    setFlash('success', 'Article supprime avec succes.');
    redirect('/articles/');
}

// Liste des articles
$articles = $pdo->query("
    SELECT a.*, c.nom as categorie_nom
    FROM articles a
    LEFT JOIN categories c ON a.categorie_id = c.id
    ORDER BY a.date_creation DESC
")->fetchAll();
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
                        <td><?= formatDate($article['date_publication']) ?></td>
                        <td class="actions">
                            <a href="/articles/modifier.php?id=<?= $article['id'] ?>" class="btn btn-secondary btn-sm">Modifier</a>
                            <a href="/articles/?supprimer=<?= $article['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
