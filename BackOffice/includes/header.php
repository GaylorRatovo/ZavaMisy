<?php
session_start();
require_once __DIR__ . '/functions.php';

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'BackOffice') ?> - Iran Conflit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .header h1 span {
            color: #e94560;
        }

        /* Navigation */
        .nav {
            background: #16213e;
            padding: 0 2rem;
            border-bottom: 3px solid #e94560;
        }

        .nav ul {
            list-style: none;
            display: flex;
            gap: 0;
        }

        .nav a {
            display: block;
            color: #ccc;
            text-decoration: none;
            padding: 1rem 1.5rem;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            margin-bottom: -3px;
        }

        .nav a:hover,
        .nav a.active {
            color: #fff;
            background: rgba(233, 69, 96, 0.1);
            border-bottom-color: #e94560;
        }

        /* Main */
        .main {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Cards */
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .card-header h2 {
            font-size: 1.25rem;
            color: #1a1a2e;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #e94560;
            color: #fff;
        }

        .btn-primary:hover {
            background: #c73e54;
        }

        .btn-secondary {
            background: #16213e;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #1a1a2e;
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #1a1a2e;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .table .actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #1a1a2e;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #e94560;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        select.form-control {
            cursor: pointer;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #e94560;
        }

        .stat-card h3 {
            font-size: 2rem;
            color: #1a1a2e;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: #666;
            font-size: 0.9rem;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        /* Image preview */
        .img-preview {
            max-width: 100px;
            max-height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }

        /* Helper classes */
        .text-muted {
            color: #666;
        }

        .text-center {
            text-align: center;
        }

        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
    </style>
</head>
<body>
    <header class="header">
        <h1>Iran <span>Conflit</span> - BackOffice</h1>
    </header>

    <nav class="nav">
        <ul>
            <li><a href="/index.php" class="<?= $currentPage === 'index' && $currentDir === 'public' ? 'active' : '' ?>">Tableau de bord</a></li>
            <li><a href="/articles/" class="<?= $currentDir === 'articles' ? 'active' : '' ?>">Articles</a></li>
            <li><a href="/categories/" class="<?= $currentDir === 'categories' ? 'active' : '' ?>">Categories</a></li>
        </ul>
    </nav>

    <main class="main">
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
