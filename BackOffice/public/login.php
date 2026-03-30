<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Admin.php';

// Si déjà connecté, rediriger vers le tableau de bord
if (isset($_SESSION['admin_id'])) {
    redirect('/index.php');
}

$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        $admin = Admin::authenticate($username, $password);
        if ($admin) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            setFlash('success', 'Bienvenue, ' . $admin['username'] . ' !');
            redirect('/index.php');
        } else {
            $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Iran Conflit BackOffice</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-primary: #1a2f4e;
            --color-primary-light: #2a4a6e;
            --color-accent: #c9a962;
            --color-accent-dark: #b08d4a;
            --color-bg: #faf9f6;
            --color-white: #ffffff;
            --color-text: #2d2d2d;
            --color-text-muted: #6b6b6b;
            --color-border: #e5e2db;
            --color-danger: #c62828;
            --color-danger-bg: #ffebee;
            --font-heading: 'Cormorant Garamond', Georgia, 'Times New Roman', serif;
            --font-body: 'Libre Baskerville', Georgia, 'Times New Roman', serif;
            --shadow-lg: 0 8px 30px rgba(26, 47, 78, 0.15);
            --radius-md: 6px;
            --radius-lg: 8px;
        }

        body {
            font-family: var(--font-body);
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .login-container {
            background: var(--color-white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h1 {
            font-family: var(--font-heading);
            font-size: 2.25rem;
            font-weight: 600;
            color: var(--color-primary);
            margin-bottom: 0.75rem;
            letter-spacing: 0.5px;
        }

        .login-header h1 span {
            color: var(--color-accent);
        }

        .login-header p {
            color: var(--color-text-muted);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            font-weight: 700;
            color: var(--color-primary);
            font-size: 0.9rem;
            letter-spacing: 0.2px;
        }

        .form-control {
            width: 100%;
            padding: 0.9rem 1.1rem;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-family: var(--font-body);
            background: var(--color-white);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(201, 169, 98, 0.15);
        }

        .btn {
            width: 100%;
            padding: 1rem 1.25rem;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: 1rem;
            font-family: var(--font-body);
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .btn-primary {
            background: var(--color-accent);
            color: var(--color-primary);
        }

        .btn-primary:hover {
            background: var(--color-accent-dark);
            box-shadow: 0 4px 12px rgba(201, 169, 98, 0.3);
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }

        .alert-error {
            background: var(--color-danger-bg);
            color: var(--color-danger);
            border-left-color: var(--color-danger);
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--color-border);
            color: var(--color-text-muted);
            font-size: 0.85rem;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem;
            }

            .login-header h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <h1>Iran <span>Conflit</span></h1>
                <p>Espace Administration</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" class="form-control"
                           value="<?= e($_POST['username'] ?? '') ?>" required autofocus
                           placeholder="Entrez votre identifiant">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" required
                           placeholder="Entrez votre mot de passe">
                </div>

                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>

            <div class="login-footer">
                Acces reserve aux administrateurs
            </div>
        </div>
    </div>
</body>
</html>
