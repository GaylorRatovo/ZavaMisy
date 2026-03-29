<?php
$pageTitle = 'Ajouter une categorie';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Ajouter une categorie</h2>
        <a href="/categories/" class="btn btn-secondary">Retour</a>
    </div>

    <form method="POST" action="/categories/traitement.php">
        <input type="hidden" name="action" value="ajouter">

        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
