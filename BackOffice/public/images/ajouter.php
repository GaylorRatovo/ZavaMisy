<?php
$pageTitle = 'Ajouter une image';
require_once __DIR__ . '/../../includes/header.php';

$pdo = getConnection();
$erreur = '';

$articles = $pdo->query("SELECT id, titre FROM articles ORDER BY titre")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alt = trim($_POST['alt'] ?? '');
    $articleId = $_POST['article_id'] ?: null;

    if (empty($alt)) {
        $erreur = 'Le texte alternatif est obligatoire.';
    } elseif (!isset($_FILES['fichier']) || $_FILES['fichier']['error'] !== UPLOAD_ERR_OK) {
        $erreur = 'Veuillez selectionner une image.';
    } else {
        $ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            $erreur = 'Format d\'image non autorise.';
        } else {
            $fileName = uniqid('img_') . '.' . $ext;
            move_uploaded_file($_FILES['fichier']['tmp_name'], __DIR__ . '/../uploads/' . $fileName);

            $stmt = $pdo->prepare("INSERT INTO images (fichier, alt, article_id) VALUES (?, ?, ?)");
            $stmt->execute([$fileName, $alt, $articleId]);
            setFlash('success', 'Image ajoutee avec succes.');
            redirect('/images/');
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2>Ajouter une image</h2>
        <a href="/images/" class="btn btn-secondary">Retour</a>
    </div>

    <?php if ($erreur): ?>
        <div class="alert alert-error"><?= e($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fichier">Image *</label>
            <input type="file" id="fichier" name="fichier" class="form-control" accept="image/*" required>
        </div>

        <div class="form-group">
            <label for="alt">Texte alternatif (alt) *</label>
            <input type="text" id="alt" name="alt" class="form-control" value="<?= e($_POST['alt'] ?? '') ?>" required>
            <small class="text-muted">Description de l'image pour l'accessibilite et le SEO</small>
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

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
