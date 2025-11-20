<?php
namespace App\Controllers;

use Core\BaseController;
use App\Services\HomeService;

class HomeController extends BaseController
{
    private HomeService $homeService;

    public function __construct()
    {
        $this->homeService = new HomeService();
    }

    public function index(): void
    {
        $this->render('home/index', $this->homeService->getHomeData());
    }
}
