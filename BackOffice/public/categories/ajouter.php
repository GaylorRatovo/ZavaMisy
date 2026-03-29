<?php
$pageTitle = 'Ajouter une categorie';
require_once __DIR__ . '/../../includes/header.php';

$pdo = getConnection();
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $slug = slugify($nom);

    if (empty($nom)) {
        $erreur = 'Le nom est obligatoire.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (nom, slug) VALUES (?, ?)");
        try {
            $stmt->execute([$nom, $slug]);
            setFlash('success', 'Categorie ajoutee avec succes.');
            redirect('/categories/');
        } catch (PDOException $e) {
            $erreur = 'Ce slug existe deja.';
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2>Ajouter une categorie</h2>
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

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
