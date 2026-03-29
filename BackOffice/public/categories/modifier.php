<?php
$pageTitle = 'Modifier une categorie';
require_once __DIR__ . '/../../includes/header.php';

$pdo = getConnection();
$erreur = '';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    redirect('/categories/');
}

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$categorie = $stmt->fetch();

if (!$categorie) {
    redirect('/categories/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $slug = slugify($nom);

    if (empty($nom)) {
        $erreur = 'Le nom est obligatoire.';
    } else {
        $stmt = $pdo->prepare("UPDATE categories SET nom = ?, slug = ? WHERE id = ?");
        try {
            $stmt->execute([$nom, $slug, $id]);
            setFlash('success', 'Categorie modifiee avec succes.');
            redirect('/categories/');
        } catch (PDOException $e) {
            $erreur = 'Ce slug existe deja.';
        }
    }
} else {
    $_POST = $categorie;
}
?>

<div class="card">
    <div class="card-header">
        <h2>Modifier la categorie</h2>
        <a href="/categories/" class="btn btn-secondary">Retour</a>
    </div>

    <?php if ($erreur): ?>
        <div class="alert alert-error"><?= e($erreur) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" value="<?= e($_POST['nom'] ?? '') ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
