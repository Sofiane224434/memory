<?php
namespace Core;
use App\Helpers\UrlHelper;

class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        foreach ($this->routes[$method] ?? [] as $route => $action) {
            if ($route === $path) {
                [$class, $method] = explode('@', $action);
                (new $class())->$method();
                return;
            }
        }
        UrlHelper::redirect('/error/404');
    }
}
