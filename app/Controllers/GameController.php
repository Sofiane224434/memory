<?php

namespace App\Controllers;

use Core\BaseController;
use App\Services\GameService;

class GameController extends BaseController
{

    public function index(): void
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        } else {
            $this->render('game/index', [
                'user' => $_SESSION['user']
            ]);
        }
    }
}