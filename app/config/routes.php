<?php

use app\controllers\DashboardController;
use app\controllers\BesoinController;
use app\controllers\DonsController;
use app\controllers\HistoriqueAchatController;
use app\middlewares\SecurityHeadersMiddleware;
use app\controllers\AchatController;
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

    $router->get('/historique-achats', [ HistoriqueAchatController::class, 'getAllAchats' ]);

    $router->group('/dons', function() use ($router) {
        $router->get('/give', [ BesoinController::class, 'all_besoins' ]);
        $router->post('/add', [ DonsController::class, 'addDon' ]); // Nouvelle route POST
        $router->get('/type-besoin/@id:[0-9]', [ BesoinController::class, 'besoin' ]);
    });

    // Routes pour les achats
    $router->get('/achats/besoins', [ AchatController::class, 'listBesoinsAcheter' ]);
    $router->get('/achats/form/@id:[0-9]', [ AchatController::class, 'showFormAchat' ]);
    $router->post('/achats/add', [ AchatController::class, 'addAchat' ]);

}, [ SecurityHeadersMiddleware::class ]);