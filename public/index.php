<?php
require __DIR__ . '/../vendor/autoload.php';

use Core\Router;

$router = new Router();
$router->get('/', 'App\\Controllers\\HomeController@index');
$router->get('/error/404', 'App\\Controllers\\ErrorController@notFound');
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
