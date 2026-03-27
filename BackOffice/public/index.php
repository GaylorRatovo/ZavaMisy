<?php

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

use ZavaMisy\BackOffice\Database;

// Configuration de base
Flight::set('flight.views.path', APP_ROOT . '/views');
Flight::set('flight.log_errors', true);

// Enregistrer une connexion PDO PostgreSQL dans le conteneur Flight
Flight::set('db', Database::getConnection());

// Routes de base
Flight::route('/', function() {
    return Flight::json(['message' => 'Back Office API is running']);
});

Flight::route('GET /health', function() {
    return Flight::json(['status' => 'ok test']);
});

// Gérer les erreurs 404
Flight::map('notFound', function() {
    Flight::response()->status(404);
    Flight::json(['error' => 'Not found'], 404);
});

// Gérer les erreurs
Flight::map('error', function(Throwable $err) {
    Flight::response()->status(500);
    Flight::json(['error' => $err->getMessage()], 500);
});

Flight::start();
