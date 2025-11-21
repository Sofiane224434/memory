<?php

namespace App\Controllers;

use App\Classes\Card;
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
        }

        // Récupérer le nombre de paires depuis le formulaire
        $pairCount = isset($_GET['pairs']) ? (int) $_GET['pairs'] : null;
        $cards = [];

        // Si un nombre de paires est sélectionné, générer les cartes
        if ($pairCount && $pairCount >= 3 && $pairCount <= 12) {
            $cards = $this->generateCards($pairCount);
        }

        $this->render('game/index', [
            'user' => $_SESSION['user'],
            'pairCount' => $pairCount,
            'cards' => $cards
        ]);
    }

    private function generateCards(int $pairCount): array
    {
        // Images disponibles pour les cartes (sans l'extension)
        $possibleValues = [
            'opbrookcard',
            'opchoppercard',
            'opfrankycard',
            'opgarpcard',
            'opjinbecard',
            'opluffycard',
            'oprobincard',
            'oprogercard',
            'opsabocard',
            'opsanjicard',
            'opshankscard',
            'opzorocard'
        ];

        // Mélanger les valeurs disponibles
        shuffle($possibleValues);

        // Prendre seulement le nombre de valeurs nécessaires
        $selectedValues = array_slice($possibleValues, 0, $pairCount);

        // Créer les paires de cartes
        $cards = [];
        $cardId = 0;

        foreach ($selectedValues as $value) {
            // Créer deux cartes avec la même valeur (une paire)
            $cards[] = new Card('card-' . $cardId++, $value);
            $cards[] = new Card('card-' . $cardId++, $value);
        }

        // Mélanger les cartes aléatoirement
        shuffle($cards);

        return $cards;
    }
}

