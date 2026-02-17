<?php

use app\controllers\DashboardController;
use app\controllers\BesoinController;
use app\controllers\DonsController;
use app\controllers\RecapController;
use app\controllers\HistoriqueAchatController;
use app\middlewares\SecurityHeadersMiddleware;
use app\controllers\AchatController;
use flight\Engine;
use flight\net\Router;
use app\controllers\ResetController;

/**
 * @var Router $router
 * @var Engine $app
 */
$router->group('', function (Router $router) use ($app) {

    $router->get('/', [DashboardController::class, 'index']);

    Flight::route('GET /dons', function () {
        $controller = new DonsController();
        $controller->getAllDons();
    });

    Flight::route('GET /dons/', function () {
        Flight::redirect('/dons');
    });

    $router->get('/historique-achats', [HistoriqueAchatController::class, 'getAllAchats']);

    $router->group('/dons', function () use ($router) {
        $router->get('/give', [BesoinController::class, 'all_besoins']);
        $router->post('/add', [DonsController::class, 'store']);
        $router->get('/besoin/@id:[0-9]', [BesoinController::class, 'besoin']);
        $router->get('/type-besoin/@id:[0-9]', [BesoinController::class, 'besoin']);
    });

    Flight::route('/recap', [new RecapController(), 'recap']);
    Flight::route('/recap/json', [new RecapController(), 'getRecapJSON']);

    Flight::route('GET /dons/simulation', [DonsController::class, 'simulation']);
    Flight::route('POST /dons/simuler', [DonsController::class, 'simuler']);
    Flight::route('POST /dons/valider', [DonsController::class, 'valider']);

    $router->get('/achats/besoins', [AchatController::class, 'listBesoinsAcheter']);
    $router->get('/achats/form/@id:[0-9]', [AchatController::class, 'showFormAchat']);
    $router->post('/achats/add', [AchatController::class, 'addAchat']);

    // Flight::route('GET /reset', [new \app\controllers\ResetController(), 'showResetPage']);
    Flight::route('POST /reset', [new ResetController(), 'resetDatabase']);


}, [SecurityHeadersMiddleware::class]);

