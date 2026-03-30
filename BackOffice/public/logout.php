<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

// Détruire la session
session_destroy();

// Rediriger vers la page de login
header('Location: /login.php');
exit;
