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
$router->get('/game', 'App\\Controllers\\GameController@index');
$router->get('/game/flip', 'App\\Controllers\\GameController@flipCard');
$router->get('/game/reset', 'App\\Controllers\\GameController@reset');
$router->get('/game/abandon', 'App\\Controllers\\GameController@abandon');
$router->get('/game/history', 'App\\Controllers\\GameController@history');
$router->get('/game/leaderboard', 'App\\Controllers\\GameController@leaderboard');
$router->get('/error/404', 'App\\Controllers\\ErrorController@notFound');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
