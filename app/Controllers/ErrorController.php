<?php
namespace App\Controllers;

use App\Services\ErrorService;
use Core\BaseController;

class ErrorController extends BaseController
{
    private ErrorService $errorService;
    public function __construct()
    {
        $this->errorService = new ErrorService();
    }
    public function notFound(): void
    {
        http_response_code(404);
        $this->render('error/404', ['message' => $this->errorService->getErrorMessage(404)]);
    }
}
