<?php

use app\controllers\AuthController;
use app\controllers\CategoryController;
use app\controllers\ObjetController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// Routes avec middleware de sécurité
$router->group('', function(Router $router) use ($app) {

	// Redirection vers la page de login
	$router->get('/', function () { Flight::redirect('/login'); });

	// Routes d'authentification
	$authController = new AuthController($app);
	$router->get('/login', [$authController, 'showLoginForm']);
	$router->post('/login', [$authController, 'login']);
	$router->get('/logout', [$authController, 'logout']);

	// Routes admin (protégées)
	$router->get('/admin/dashboard', [$authController, 'dashboard']);

	// Routes admin - Catégories
	$categoryController = new CategoryController($app);
	$router->get('/admin/categories', [$categoryController, 'index']);
	$router->get('/admin/categories/create', [$categoryController, 'create']);
	$router->post('/admin/categories/store', [$categoryController, 'store']);
	$router->post('/admin/categories/delete', [$categoryController, 'delete']);

	// Routes admin - Objets
	$objetController = new ObjetController($app);
	$router->get('/admin/objets', [$objetController, 'index']);
	$router->get('/admin/objets/create', [$objetController, 'create']);
	$router->post('/admin/objets/store', [$objetController, 'store']);
	$router->get('/admin/objets/@id/categorie', [$objetController, 'editCategorie']);
	$router->post('/admin/objets/categorie/update', [$objetController, 'updateCategorie']);
	
}, [ SecurityHeadersMiddleware::class ]);