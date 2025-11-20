<?php
require __DIR__ . '/../vendor/autoload.php';

use Core\Router;

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Démarrer la session
session_start();

$router = new Router();
$router->get('/', 'App\\Controllers\\HomeController@index');
$router->get('/register', 'App\\Controllers\\AuthController@showRegister');
$router->post('/register', 'App\\Controllers\\AuthController@register');
$router->get('/login', 'App\\Controllers\\AuthController@showLogin');
$router->post('/login', 'App\\Controllers\\AuthController@login');
$router->get('/logout', 'App\\Controllers\\AuthController@logout');
$router->get('/error/404', 'App\\Controllers\\ErrorController@notFound');
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
