<?php
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/Categorie.php';
require_once __DIR__ . '/../../models/Image.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    redirect('/articles/');
}

$article = Article::findById((int) $id);
if (!$article) {
    redirect('/articles/');
}

$pageTitle = 'Modifier un article';
require_once __DIR__ . '/../../includes/header.php';

$categories = Categorie::findAllSimple();
$images = Image::findByArticleId((int) $id);

$datePublication = '';
if ($article['date_publication']) {
    $datePublication = date('Y-m-d\TH:i', strtotime($article['date_publication']));
}
?>

<div class="card">
    <div class="card-header">
        <h2>Modifier l'article</h2>
        <a href="/articles/" class="btn btn-secondary">Retour</a>
    </div>

    <form method="POST" action="/articles/traitement.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="modifier">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" class="form-control" value="<?= e($article['titre']) ?>" required>
        </div>

        <div class="form-group">
            <label for="extrait">Extrait</label>
            <textarea id="extrait" name="extrait" class="form-control" rows="2"><?= e($article['extrait']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="contenu">Contenu *</label>
            <textarea id="contenu" name="contenu" class="form-control"><?= e($article['contenu']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="categorie_id">Categorie</label>
            <select id="categorie_id" name="categorie_id" class="form-control">
                <option value="">-- Selectionner --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $article['categorie_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= e($cat['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Image principale</label>
            <?php if ($article['image']): ?>
                <div style="margin-bottom: 0.5rem;">
                    <img src="/uploads/<?= e($article['image']) ?>" alt="<?= e($article['image_alt']) ?>" class="img-preview">
                    <span class="text-muted" style="margin-left: 0.5rem;"><?= e($article['image']) ?></span>
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label for="image_alt">Texte alternatif (alt)</label>
            <input type="text" id="image_alt" name="image_alt" class="form-control" value="<?= e($article['image_alt']) ?>">
        </div>

        <div class="form-group">
            <label for="date_publication">Date de publication</label>
            <input type="datetime-local" id="date_publication" name="date_publication" class="form-control" value="<?= $datePublication ?>">
        </div>

        <hr style="margin: 1.5rem 0;">
        <h3 style="margin-bottom: 1rem; font-size: 1rem;">SEO</h3>

        <div class="form-group">
            <label for="meta_titre">Meta titre (max 70 caracteres)</label>
            <input type="text" id="meta_titre" name="meta_titre" class="form-control" maxlength="70" value="<?= e($article['meta_titre']) ?>">
        </div>

        <div class="form-group">
            <label for="meta_description">Meta description (max 160 caracteres)</label>
            <textarea id="meta_description" name="meta_description" class="form-control" rows="2" maxlength="160"><?= e($article['meta_description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<!-- Images supplementaires -->
<div class="card">
    <div class="card-header">
        <h2>Images supplementaires</h2>
    </div>

    <?php if (empty($images)): ?>
        <p class="text-muted">Aucune image supplementaire.</p>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <?php foreach ($images as $img): ?>
                <div style="text-align: center; padding: 0.5rem; border: 1px solid #eee; border-radius: 4px;">
                    <img src="/uploads/<?= e($img['fichier']) ?>" alt="<?= e($img['alt']) ?>" style="max-width: 100%; max-height: 100px; object-fit: cover; border-radius: 4px;">
                    <p style="font-size: 0.8rem; margin: 0.5rem 0; color: #666;"><?= e($img['alt']) ?></p>
                    <a href="/articles/traitement.php?action=supprimer_image&image_id=<?= $img['id'] ?>&article_id=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette image ?')">Supprimer</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <hr style="margin: 1rem 0;">
    <h4 style="font-size: 0.95rem; margin-bottom: 1rem;">Ajouter une image</h4>

    <form method="POST" action="/articles/traitement.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ajouter_image">
        <input type="hidden" name="article_id" value="<?= $id ?>">

        <div class="form-group">
            <label for="fichier">Image *</label>
            <input type="file" id="fichier" name="fichier" class="form-control" accept="image/*" required>
        </div>

        <div class="form-group">
            <label for="alt">Texte alternatif (alt) *</label>
            <input type="text" id="alt" name="alt" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-secondary">Ajouter l'image</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
