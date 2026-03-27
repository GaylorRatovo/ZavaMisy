<?php

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

// Configuration de base (le FrontOffice n'utilise pas directement la base)
Flight::set('flight.views.path', APP_ROOT . '/views');
Flight::set('flight.log_errors', true);

// Routes de base
Flight::route('/', function() {
    return Flight::view('home');
});

Flight::route('GET /api/health', function() {
    return Flight::json(['status' => 'ok']);
});

// Gérer les erreurs 404
Flight::map('notFound', function() {
    Flight::response()->status(404);
    Flight::view('404');
});

// Gérer les erreurs
Flight::map('error', function(Throwable $err) {
    Flight::response()->status(500);
    Flight::view('error');
});

Flight::start();
