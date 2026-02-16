<?php

use app\controllers\DashboardController;
use app\controllers\BesoinController;
use app\controllers\DonsController;
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
        $router->post('/add', [ DonsController::class, 'addDon' ]); // Nouvelle route POST
        $router->get('/type-besoin/@id:[0-9]', [ BesoinController::class, 'besoin' ]);
    });	
}, [ SecurityHeadersMiddleware::class ]);