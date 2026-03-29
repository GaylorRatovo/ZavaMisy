<?php
require_once __DIR__ . '/../../models/Categorie.php';

$pageTitle = 'Categories';
require_once __DIR__ . '/../../includes/header.php';

$categories = Categorie::findAll();
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
                            <a href="/categories/traitement.php?action=supprimer&id=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette categorie ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
