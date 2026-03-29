<?php
$pageTitle = 'Modifier un article';
require_once __DIR__ . '/../../includes/header.php';

$pdo = getConnection();
$erreur = '';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    redirect('/articles/');
}

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    redirect('/articles/');
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $slug = slugify($titre);
    $contenu = trim($_POST['contenu'] ?? '');
    $extrait = trim($_POST['extrait'] ?? '');
    $categorieId = $_POST['categorie_id'] ?: null;
    $metaTitre = trim($_POST['meta_titre'] ?? '');
    $metaDescription = trim($_POST['meta_description'] ?? '');
    $datePublication = $_POST['date_publication'] ?: null;
    $imageAlt = trim($_POST['image_alt'] ?? '');

    if (empty($titre) || empty($contenu)) {
        $erreur = 'Le titre et le contenu sont obligatoires.';
    } else {
        $imageName = $article['image'];

        // Upload nouvelle image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($ext, $allowed)) {
                // Supprimer ancienne image
                if ($article['image']) {
                    $oldPath = __DIR__ . '/../uploads/' . $article['image'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $imageName = uniqid('article_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $imageName);
            } else {
                $erreur = 'Format d\'image non autorise.';
            }
        }

        if (!$erreur) {
            $stmt = $pdo->prepare("
                UPDATE articles SET
                    titre = ?, slug = ?, contenu = ?, extrait = ?,
                    image = ?, image_alt = ?, meta_titre = ?, meta_description = ?,
                    categorie_id = ?, date_publication = ?
                WHERE id = ?
            ");
            $stmt->execute([$titre, $slug, $contenu, $extrait, $imageName, $imageAlt, $metaTitre, $metaDescription, $categorieId, $datePublication, $id]);
            setFlash('success', 'Article modifie avec succes.');
            redirect('/articles/');
        }
    }
} else {
    $_POST = $article;
    if ($article['date_publication']) {
        $_POST['date_publication'] = date('Y-m-d\TH:i', strtotime($article['date_publication']));
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2>Modifier l'article</h2>
        <a href="/articles/" class="btn btn-secondary">Retour</a>
    </div>

    <?php if ($erreur): ?>
        <div class="alert alert-error"><?= e($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" class="form-control" value="<?= e($_POST['titre'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="extrait">Extrait</label>
            <textarea id="extrait" name="extrait" class="form-control" rows="2"><?= e($_POST['extrait'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="contenu">Contenu *</label>
            <textarea id="contenu" name="contenu" class="form-control" required><?= e($_POST['contenu'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="categorie_id">Categorie</label>
            <select id="categorie_id" name="categorie_id" class="form-control">
                <option value="">-- Selectionner --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($_POST['categorie_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
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
            <input type="text" id="image_alt" name="image_alt" class="form-control" value="<?= e($_POST['image_alt'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="date_publication">Date de publication</label>
            <input type="datetime-local" id="date_publication" name="date_publication" class="form-control" value="<?= e($_POST['date_publication'] ?? '') ?>">
        </div>

        <hr style="margin: 1.5rem 0;">
        <h3 style="margin-bottom: 1rem; font-size: 1rem;">SEO</h3>

        <div class="form-group">
            <label for="meta_titre">Meta titre (max 70 caracteres)</label>
            <input type="text" id="meta_titre" name="meta_titre" class="form-control" maxlength="70" value="<?= e($_POST['meta_titre'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="meta_description">Meta description (max 160 caracteres)</label>
            <textarea id="meta_description" name="meta_description" class="form-control" rows="2" maxlength="160"><?= e($_POST['meta_description'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
