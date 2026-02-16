<?php

use app\controllers\DashboardController;
use app\controllers\BesoinController;
use app\controllers\DonsController;
use app\controllers\RecapController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router
 * @var Engine $app
 */
$router->group('', function(Router $router) use ($app) {

    $router->get('/', [DashboardController::class, 'index']);

    Flight::route('GET /dons', function () {
        $controller = new DonsController();
        $controller->getAllDons();
    });

    Flight::route('GET /dons/', function () {
        Flight::redirect('/dons');
    });

    $router->group('/dons', function() use ($router) {
        $router->get('/give', [ BesoinController::class, 'all_besoins' ]);
        $router->post('/add', [ DonsController::class, 'store' ]); // Utiliser store au lieu de addDon
        $router->get('/besoin/@id:[0-9]', [ BesoinController::class, 'besoin' ]);
        $router->get('/type-besoin/@id:[0-9]', [ BesoinController::class, 'besoin' ]);
    });

    Flight::route('/recap', [new RecapController(), 'recap']);
    Flight::route('/recap/json', [new RecapController(), 'getRecapJSON']);

}, [ SecurityHeadersMiddleware::class ]);

// Routes pour la simulation
Flight::route('GET /dons/simulation', [DonsController::class, 'simulation']);
Flight::route('POST /dons/simuler', [DonsController::class, 'simuler']);
Flight::route('POST /dons/valider', [DonsController::class, 'valider']);
