<?php

use app\controllers\AuthController;
use app\controllers\CategoryController;
use app\controllers\ObjetController;
use app\controllers\UserObjetController;
use app\controllers\UserEchangeController;
use app\middlewares\SecurityHeadersMiddleware;
use app\controllers\UserController;
use app\controllers\AccueilController;
use app\controllers\EchangeController;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// Routes avec middleware de sécurité
$router->group('', function(Router $router) use ($app) {

	// Redirection vers la page de login
	$router->get('/', function () { Flight::redirect('/user/login'); });

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

	// Routes admin - Objets (consultation et modification catégorie uniquement)
	$objetController = new ObjetController($app);
	$router->get('/admin/objets', [$objetController, 'index']);
	$router->get('/admin/objets/@id/categorie', [$objetController, 'editCategorie']);
	$router->post('/admin/objets/categorie/update', [$objetController, 'updateCategorie']);

	// Routes utilisateur - Gestion des objets
	$userObjetController = new UserObjetController($app);
	$router->get('/user/objets', [$userObjetController, 'index']);
	$router->get('/user/objets/create', [$userObjetController, 'create']);
	$router->post('/user/objets/store', [$userObjetController, 'store']);
	$router->get('/user/objets/@id/edit', [$userObjetController, 'edit']);
	$router->post('/user/objets/update', [$userObjetController, 'update']);
	$router->post('/user/objets/delete', [$userObjetController, 'delete']);
	$router->post('/user/objets/image/delete', [$userObjetController, 'deleteImage']);

	// Routes utilisateur - Gestion des échanges
	$userEchangeController = new UserEchangeController($app);
	$router->get('/user/echanges', [$userEchangeController, 'index']);
	$router->post('/user/echanges/annuler', [$userEchangeController, 'annuler']);
	$router->post('/user/echanges/accepter', [$userEchangeController, 'accepter']);
	$router->post('/user/echanges/refuser', [$userEchangeController, 'refuser']);

	// routes utilisateur - authentification
	$userController = new UserController($app);
	$router->get('/user/login', [$userController, 'showLoginForm']);
	$router->post('/user/login/authenticate', [$userController, 'login']);
	$router->get('/user/inscription', [$userController, 'showRegistrationForm']);
	$router->post('/user/inscription/register', [$userController, 'register']);

	// route accueil
	$accueilController = new AccueilController($app);
	$router->get('/accueil/accueil', [$accueilController, 'getAllObject']);
	$router->get('/accueil/accueil/@idUser', [$accueilController, 'getAllObject']); 

	$router->get('/carteObjet', [$accueilController, 'showCarteObjet']);
	$router->get('/carteObjet/@id', [$accueilController, 'showCarteObjet']);

	$router->get('/demandeEchange/@id', [$accueilController, 'showDemandeEchangeForm']);
	$router->post('/demandeEchange', [$accueilController,'insertEchange']);

	// route utilisateur Dashbord
	$router->get('/admin/utilisateurs',[$userController,'AllUtilisateurs']);

	// route gestion des échanges admin
	$EchangeController = new EchangeController($app);
	$router->get('/admin/echanges', [$EchangeController, 'getAllObjects']);

	$router->get('/afficherHistoriqueEchange/@id', [$EchangeController, 'historiqueEchange']);
	$router->get('/rechercherEchange', [$EchangeController, 'rechercherParDate']);

}, [ SecurityHeadersMiddleware::class ]);