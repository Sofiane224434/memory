<?php
namespace Core;

class BaseController
{
    protected function render(string $view, array $params = []): void
    {
        extract($params, EXTR_SKIP);
        ob_start();
        require __DIR__ . '/../app/Views/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../app/Views/layouts/base.php';
    }
}
