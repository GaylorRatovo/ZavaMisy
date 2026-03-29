<?php
require_once __DIR__ . '/../../models/Categorie.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    redirect('/categories/');
}

$categorie = Categorie::findById((int) $id);
if (!$categorie) {
    redirect('/categories/');
}

$pageTitle = 'Modifier une categorie';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Modifier la categorie</h2>
        <a href="/categories/" class="btn btn-secondary">Retour</a>
    </div>

    <form method="POST" action="/categories/traitement.php">
        <input type="hidden" name="action" value="modifier">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" value="<?= e($categorie['nom']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
