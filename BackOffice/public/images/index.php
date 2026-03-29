<?php
$pageTitle = 'Images';
require_once __DIR__ . '/../../includes/header.php';

$pdo = getConnection();

// Suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $stmt = $pdo->prepare("SELECT fichier FROM images WHERE id = ?");
    $stmt->execute([$_GET['supprimer']]);
    $image = $stmt->fetch();

    if ($image) {
        $imagePath = __DIR__ . '/../uploads/' . $image['fichier'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
        $stmt->execute([$_GET['supprimer']]);
        setFlash('success', 'Image supprimee avec succes.');
    }
    redirect('/images/');
}

// Liste des images avec article associé
$images = $pdo->query("
    SELECT i.*, a.titre as article_titre
    FROM images i
    LEFT JOIN articles a ON i.article_id = a.id
    ORDER BY i.date_creation DESC
")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h2>Gestion des images</h2>
        <a href="/images/ajouter.php" class="btn btn-primary">Ajouter une image</a>
    </div>

    <?php if (empty($images)): ?>
        <p class="text-muted text-center">Aucune image.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Apercu</th>
                    <th>Alt</th>
                    <th>Article</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($images as $img): ?>
                    <tr>
                        <td>
                            <img src="/uploads/<?= e($img['fichier']) ?>" alt="<?= e($img['alt']) ?>" class="img-preview">
                        </td>
                        <td><?= e($img['alt']) ?></td>
                        <td><?= e($img['article_titre'] ?? '-') ?></td>
                        <td><?= formatDate($img['date_creation']) ?></td>
                        <td class="actions">
                            <a href="/images/modifier.php?id=<?= $img['id'] ?>" class="btn btn-secondary btn-sm">Modifier</a>
                            <a href="/images/?supprimer=<?= $img['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette image ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
