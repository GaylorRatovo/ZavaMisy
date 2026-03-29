<?php
/**
 * Traitement des actions pour les catégories
 */

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../models/Categorie.php';

$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'ajouter':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');

            if (empty($nom)) {
                setFlash('error', 'Le nom est obligatoire.');
                redirect('/categories/ajouter.php');
            }

            try {
                Categorie::create($nom, slugify($nom));
                setFlash('success', 'Categorie ajoutee avec succes.');
                redirect('/categories/');
            } catch (PDOException $e) {
                setFlash('error', 'Ce slug existe deja.');
                redirect('/categories/ajouter.php');
            }
        }
        break;

    case 'modifier':
        $id = $_POST['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $nom = trim($_POST['nom'] ?? '');

            if (empty($nom)) {
                setFlash('error', 'Le nom est obligatoire.');
                redirect('/categories/modifier.php?id=' . $id);
            }

            try {
                Categorie::update((int) $id, $nom, slugify($nom));
                setFlash('success', 'Categorie modifiee avec succes.');
                redirect('/categories/');
            } catch (PDOException $e) {
                setFlash('error', 'Ce slug existe deja.');
                redirect('/categories/modifier.php?id=' . $id);
            }
        }
        break;

    case 'supprimer':
        $id = $_GET['id'] ?? null;
        if ($id && is_numeric($id)) {
            Categorie::delete((int) $id);
            setFlash('success', 'Categorie supprimee avec succes.');
        }
        redirect('/categories/');
        break;

    default:
        redirect('/categories/');
}
