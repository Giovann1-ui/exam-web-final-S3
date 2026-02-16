<?php

use app\controllers\TypeBesoinController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {

	$router->get('/', function() use ($app) {
		$app->render('welcome', [ 'message' => 'You are gonna do great things!' ]);
	});

	$router->get('/hello-world/@name', function($name) {
		echo '<h1>Hello world! Oh hey '.$name.'!</h1>';
	});

	$router->group('/dons', function() use ($router) {
		$router->get('/give', [ TypeBesoinController::class, 'all_type_besoins' ]);
		$router->get('/type-besoin/@id:[0-9]', [ TypeBesoinController::class, 'besoin' ]);
	});	
}, [ SecurityHeadersMiddleware::class ]);