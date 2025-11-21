<?php

namespace App\Controllers;

use App\Classes\Card;
use Core\BaseController;
use App\Services\GameService;
use App\Models\GameModel;

class GameController extends BaseController
{
    private GameService $gameService;
    private GameModel $gameModel;

    public function __construct()
    {
        $this->gameService = new GameService();
        $this->gameModel = new GameModel();
    }

    public function index(): void
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Marquer toutes les anciennes parties "en cours" comme abandonnées
        // (si l'utilisateur arrive sur la page sans session de jeu active)
        if (!isset($_SESSION['game'])) {
            $this->gameModel->abandonUserInProgressGames($_SESSION['user']['id']);
        }

        // Récupérer le nombre de paires depuis le formulaire
        $pairCount = isset($_GET['pairs']) ? (int) $_GET['pairs'] : null;
        $cards = [];
        $gameState = null;

        // Si un nombre de paires est sélectionné, initialiser le jeu
        if ($pairCount && $pairCount >= 3 && $pairCount <= 12) {
            // Marquer toute partie en cours comme abandonnée avant d'en créer une nouvelle
            $this->gameModel->abandonUserInProgressGames($_SESSION['user']['id']);

            // Créer une nouvelle partie en base de données
            $gameDbId = $this->gameModel->createGame($_SESSION['user']['id'], $pairCount);

            // Initialiser le jeu via le service
            $gameState = $this->gameService->initializeGame($pairCount);
            $gameState['gameDbId'] = $gameDbId; // Stocker l'ID de la partie en BDD

            // Stocker l'état du jeu en session
            $_SESSION['game'] = $gameState;

            $cards = $gameState['cards'];
        } elseif (isset($_SESSION['game'])) {
            // Récupérer la partie en cours
            $gameState = $_SESSION['game'];
            $pairCount = $gameState['totalPairs'];
            $cards = $gameState['cards'];
        }

        $this->render('game/index', [
            'user' => $_SESSION['user'],
            'pairCount' => $pairCount,
            'cards' => $cards,
            'gameState' => $gameState
        ]);
    }

    /**
     * Retourner une carte (GET)
     */
    public function flipCard(): void
    {
        if (!isset($_SESSION['user']) || !isset($_SESSION['game'])) {
            // Si c'est une requête AJAX, renvoyer JSON
            if (
                !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
            ) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Partie non initialisée']);
                exit;
            }
            header('Location: /game');
            exit;
        }

        $cardId = $_GET['cardId'] ?? null;

        if (!$cardId) {
            // Si c'est une requête AJAX, renvoyer JSON
            if (
                !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
            ) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ID de carte manquant']);
                exit;
            }
            header('Location: /game');
            exit;
        }

        // Retourner la carte via le service
        $result = $this->gameService->flipCard($_SESSION['game'], $cardId);

        // Mettre à jour la partie en base de données
        if (isset($_SESSION['game']['gameDbId'])) {
            $timeElapsed = time() - $_SESSION['game']['startTime'];
            $status = $result['gameOver'] ?? false ? 'completed' : 'in_progress';

            $this->gameModel->updateGame(
                $_SESSION['game']['gameDbId'],
                $_SESSION['game']['moves'],
                $timeElapsed,
                $status
            );
        }

        // Si c'est une requête AJAX, renvoyer JSON
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }

        // Sinon, rediriger vers la page du jeu
        header('Location: /game');
        exit;
    }

    /**
     * Réinitialiser le jeu
     */
    public function reset(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Marquer la partie en cours comme abandonnée si elle existe
        if (isset($_SESSION['game']['gameDbId'])) {
            $this->gameModel->abandonGame($_SESSION['game']['gameDbId']);
        }

        if (isset($_SESSION['game'])) {
            unset($_SESSION['game']);
        }

        header('Location: /game');
        exit;
    }

    /**
     * Abandonner la partie en cours
     */
    public function abandon(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Marquer la partie en cours comme abandonnée
        if (isset($_SESSION['game']['gameDbId'])) {
            $this->gameModel->abandonGame($_SESSION['game']['gameDbId']);
        }

        if (isset($_SESSION['game'])) {
            unset($_SESSION['game']);
        }

        header('Location: /game/history');
        exit;
    }

    /**
     * Afficher l'historique des parties
     */
    public function history(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $games = $this->gameModel->getUserGames($_SESSION['user']['id'], 20);

        $this->render('game/history', [
            'user' => $_SESSION['user'],
            'games' => $games
        ]);
    }

    /**
     * Afficher le classement
     */
    public function leaderboard(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $topScores = $this->gameModel->getTopScores(20);

        $this->render('game/leaderboard', [
            'user' => $_SESSION['user'],
            'topScores' => $topScores
        ]);
    }
}

