<?php
/**
 * Point d'entrée de l'application
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Démarrer la session
session_start();

// Charger les configurations
$dbConfig = require __DIR__ . '/../config/database.php';
$appConfig = require __DIR__ . '/../config/app.php';

// Configuration de Flight
Flight::set('flight.views.path', __DIR__ . '/../app/Views');
Flight::set('flight.log_errors', $appConfig['debug']);

// Connexion à la base de données
try {
    $port = $dbConfig['port'] ?? 3306;
    $dsn = "{$dbConfig['driver']}:host={$dbConfig['host']};port={$port};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
    Flight::register('db', 'PDO', [$dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']]);
} catch (PDOException $e) {
    if ($appConfig['debug']) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
    die("Erreur de connexion à la base de données");
}

// Charger les routes
require_once __DIR__ . '/../app/routes.php';

// Démarrer l'application
Flight::start();
