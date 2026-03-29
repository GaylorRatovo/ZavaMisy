<?php
$pageTitle = 'Modifier une image';
require_once __DIR__ . '/../../includes/header.php';

$pdo = getConnection();
$erreur = '';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    redirect('/images/');
}

$stmt = $pdo->prepare("SELECT * FROM images WHERE id = ?");
$stmt->execute([$id]);
$image = $stmt->fetch();

if (!$image) {
    redirect('/images/');
}

$articles = $pdo->query("SELECT id, titre FROM articles ORDER BY titre")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alt = trim($_POST['alt'] ?? '');
    $articleId = $_POST['article_id'] ?: null;

    if (empty($alt)) {
        $erreur = 'Le texte alternatif est obligatoire.';
    } else {
        $fileName = $image['fichier'];

        // Nouvelle image
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($ext, $allowed)) {
                // Supprimer ancienne
                $oldPath = __DIR__ . '/../uploads/' . $image['fichier'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }

                $fileName = uniqid('img_') . '.' . $ext;
                move_uploaded_file($_FILES['fichier']['tmp_name'], __DIR__ . '/../uploads/' . $fileName);
            } else {
                $erreur = 'Format d\'image non autorise.';
            }
        }

        if (!$erreur) {
            $stmt = $pdo->prepare("UPDATE images SET fichier = ?, alt = ?, article_id = ? WHERE id = ?");
            $stmt->execute([$fileName, $alt, $articleId, $id]);
            setFlash('success', 'Image modifiee avec succes.');
            redirect('/images/');
        }
    }
} else {
    $_POST = $image;
}
?>

<div class="card">
    <div class="card-header">
        <h2>Modifier l'image</h2>
        <a href="/images/" class="btn btn-secondary">Retour</a>
    </div>

    <?php if ($erreur): ?>
        <div class="alert alert-error"><?= e($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Image actuelle</label>
            <div style="margin-bottom: 0.5rem;">
                <img src="/uploads/<?= e($image['fichier']) ?>" alt="<?= e($image['alt']) ?>" style="max-width: 200px; border-radius: 4px;">
            </div>
        </div>

        <div class="form-group">
            <label for="fichier">Remplacer l'image</label>
            <input type="file" id="fichier" name="fichier" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label for="alt">Texte alternatif (alt) *</label>
            <input type="text" id="alt" name="alt" class="form-control" value="<?= e($_POST['alt'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="article_id">Associer a un article</label>
            <select id="article_id" name="article_id" class="form-control">
                <option value="">-- Aucun --</option>
                <?php foreach ($articles as $art): ?>
                    <option value="<?= $art['id'] ?>" <?= ($_POST['article_id'] ?? '') == $art['id'] ? 'selected' : '' ?>>
                        <?= e($art['titre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
