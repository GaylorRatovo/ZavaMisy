<?php
$pageTitle = 'Categories';
require_once __DIR__ . '/../../includes/header.php';

$pdo = getConnection();

// Suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET['supprimer']]);
    setFlash('success', 'Categorie supprimee avec succes.');
    redirect('/categories/');
}

// Liste des catégories avec nombre d'articles
$categories = $pdo->query("
    SELECT c.*, COUNT(a.id) as nb_articles
    FROM categories c
    LEFT JOIN articles a ON c.id = a.categorie_id
    GROUP BY c.id
    ORDER BY c.nom
")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h2>Gestion des categories</h2>
        <a href="/categories/ajouter.php" class="btn btn-primary">Nouvelle categorie</a>
    </div>

    <?php if (empty($categories)): ?>
        <p class="text-muted text-center">Aucune categorie.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= e($cat['nom']) ?></td>
                        <td><code><?= e($cat['slug']) ?></code></td>
                        <td><?= $cat['nb_articles'] ?></td>
                        <td class="actions">
                            <a href="/categories/modifier.php?id=<?= $cat['id'] ?>" class="btn btn-secondary btn-sm">Modifier</a>
                            <a href="/categories/?supprimer=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette categorie ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
