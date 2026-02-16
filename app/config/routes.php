<?php

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

    $router->get('/', function() use ($app) {
        $app->render('welcome');
    });

    Flight::route('GET /dons', function () {
        $controller = new DonsController();
        $controller->getAllDons();
    });

    Flight::route('GET /dons/', function () {
        Flight::redirect('/dons');
    });

	$router->group('/dons', function() use ($router) {
		$router->get('/give', [ BesoinController::class, 'all_besoins' ]);
		$router->get('/type-besoin/@id:[0-9]', [ BesoinController::class, 'besoin' ]);
	});	
}, [ SecurityHeadersMiddleware::class ]);