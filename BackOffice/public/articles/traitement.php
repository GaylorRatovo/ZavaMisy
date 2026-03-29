<?php
/**
 * Traitement des actions pour les articles
 */

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/Image.php';

$action = $_GET['action'] ?? $_POST['action'] ?? null;

/**
 * Upload une image et retourne son nom
 */
function uploadImage(array $file, string $prefix = 'article_'): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($ext, $allowed)) {
        return null;
    }

    $fileName = $prefix . uniqid() . '.' . $ext;
    $destination = __DIR__ . '/../uploads/' . $fileName;
    move_uploaded_file($file['tmp_name'], $destination);

    return $fileName;
}

/**
 * Supprime une image du serveur
 */
function deleteImageFile(?string $fileName): void
{
    if ($fileName) {
        $path = __DIR__ . '/../uploads/' . $fileName;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}

switch ($action) {
    case 'ajouter':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre'] ?? '');
            $contenu = trim($_POST['contenu'] ?? '');

            if (empty($titre) || empty($contenu)) {
                setFlash('error', 'Le titre et le contenu sont obligatoires.');
                redirect('/articles/ajouter.php');
            }

            $imageName = null;
            if (isset($_FILES['image'])) {
                $imageName = uploadImage($_FILES['image']);
            }

            Article::create([
                'titre' => $titre,
                'slug' => slugify($titre),
                'contenu' => $contenu,
                'extrait' => trim($_POST['extrait'] ?? ''),
                'image' => $imageName,
                'image_alt' => trim($_POST['image_alt'] ?? ''),
                'meta_titre' => trim($_POST['meta_titre'] ?? ''),
                'meta_description' => trim($_POST['meta_description'] ?? ''),
                'categorie_id' => $_POST['categorie_id'] ?: null,
                'date_publication' => $_POST['date_publication'] ?: null
            ]);

            setFlash('success', 'Article ajoute avec succes.');
            redirect('/articles/');
        }
        break;

    case 'modifier':
        $id = $_POST['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $titre = trim($_POST['titre'] ?? '');
            $contenu = trim($_POST['contenu'] ?? '');

            if (empty($titre) || empty($contenu)) {
                setFlash('error', 'Le titre et le contenu sont obligatoires.');
                redirect('/articles/modifier.php?id=' . $id);
            }

            $article = Article::findById((int) $id);
            $imageName = $article['image'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                deleteImageFile($article['image']);
                $imageName = uploadImage($_FILES['image']);
            }

            Article::update((int) $id, [
                'titre' => $titre,
                'slug' => slugify($titre),
                'contenu' => $contenu,
                'extrait' => trim($_POST['extrait'] ?? ''),
                'image' => $imageName,
                'image_alt' => trim($_POST['image_alt'] ?? ''),
                'meta_titre' => trim($_POST['meta_titre'] ?? ''),
                'meta_description' => trim($_POST['meta_description'] ?? ''),
                'categorie_id' => $_POST['categorie_id'] ?: null,
                'date_publication' => $_POST['date_publication'] ?: null
            ]);

            setFlash('success', 'Article modifie avec succes.');
            redirect('/articles/');
        }
        break;

    case 'supprimer':
        $id = $_GET['id'] ?? null;
        if ($id && is_numeric($id)) {
            $article = Article::findById((int) $id);
            if ($article) {
                deleteImageFile($article['image']);
            }

            // Supprimer les fichiers des images associées
            $images = Image::findByArticleId((int) $id);
            foreach ($images as $img) {
                deleteImageFile($img['fichier']);
            }

            Article::delete((int) $id);
            setFlash('success', 'Article supprime avec succes.');
        }
        redirect('/articles/');
        break;

    case 'ajouter_image':
        $articleId = $_POST['article_id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $articleId) {
            $alt = trim($_POST['alt'] ?? '');

            if (empty($alt)) {
                setFlash('error', 'Le texte alternatif est obligatoire.');
                redirect('/articles/modifier.php?id=' . $articleId);
            }

            if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
                $fileName = uploadImage($_FILES['fichier'], 'img_');
                if ($fileName) {
                    Image::create($fileName, $alt, (int) $articleId);
                    setFlash('success', 'Image ajoutee avec succes.');
                } else {
                    setFlash('error', 'Format d\'image non autorise.');
                }
            } else {
                setFlash('error', 'Veuillez selectionner une image.');
            }
            redirect('/articles/modifier.php?id=' . $articleId);
        }
        break;

    case 'supprimer_image':
        $imageId = $_GET['image_id'] ?? null;
        $articleId = $_GET['article_id'] ?? null;
        if ($imageId && is_numeric($imageId)) {
            $image = Image::findById((int) $imageId);
            if ($image) {
                deleteImageFile($image['fichier']);
                Image::delete((int) $imageId);
                setFlash('success', 'Image supprimee.');
            }
        }
        redirect('/articles/modifier.php?id=' . $articleId);
        break;

    default:
        redirect('/articles/');
}
