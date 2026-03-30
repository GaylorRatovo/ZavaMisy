<?php
require_once __DIR__ . '/../../models/Categorie.php';

$pageTitle = 'Ajouter un article';
require_once __DIR__ . '/../../includes/header.php';

$categories = Categorie::findAllSimple();
?>

<div class="card">
    <div class="card-header">
        <h2>Ajouter un article</h2>
        <a href="/articles/" class="btn btn-secondary">Retour</a>
    </div>

    <form method="POST" action="/articles/traitement.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ajouter">

        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="extrait">Extrait</label>
            <textarea id="extrait" name="extrait" class="form-control" rows="2"></textarea>
        </div>

        <div class="form-group">
            <label for="contenu">Contenu *</label>
            <textarea id="contenu" name="contenu" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="categorie_id">Categorie</label>
            <select id="categorie_id" name="categorie_id" class="form-control">
                <option value="">-- Selectionner --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= e($cat['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Image principale</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label for="image_alt">Texte alternatif (alt)</label>
            <input type="text" id="image_alt" name="image_alt" class="form-control">
        </div>

        <div class="form-group">
            <label for="date_publication">Date de publication</label>
            <input type="datetime-local" id="date_publication" name="date_publication" class="form-control">
        </div>

        <hr style="margin: 1.5rem 0;">
        <h3 style="margin-bottom: 1rem; font-size: 1rem;">SEO</h3>

        <div class="form-group">
            <label for="meta_titre">Meta titre (max 70 caracteres)</label>
            <input type="text" id="meta_titre" name="meta_titre" class="form-control" maxlength="70">
        </div>

        <div class="form-group">
            <label for="meta_description">Meta description (max 160 caracteres)</label>
            <textarea id="meta_description" name="meta_description" class="form-control" rows="2" maxlength="160"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
