<?php

namespace App\Services;

use App\Classes\Card;

class GameService
{
    /**
     * Initialiser une nouvelle partie
     */
    public function initializeGame(int $pairCount): array
    {
        $cards = $this->generateCards($pairCount);

        return [
            'cards' => $cards,
            'moves' => 0,
            'matchedPairs' => 0,
            'totalPairs' => $pairCount,
            'startTime' => time(),
            'flippedCards' => []
        ];
    }

    /**
     * Générer les cartes pour le jeu
     */
    private function generateCards(int $pairCount): array
    {
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

        shuffle($possibleValues);
        $selectedValues = array_slice($possibleValues, 0, $pairCount);

        $cards = [];
        $cardId = 0;

        foreach ($selectedValues as $value) {
            $cards[] = new Card('card-' . $cardId++, $value);
            $cards[] = new Card('card-' . $cardId++, $value);
        }

        shuffle($cards);

        return $cards;
    }

    /**
     * Retourner une carte
     */
    public function flipCard(array &$gameState, string $cardId): array
    {
        $cards = $gameState['cards'];
        $card = $this->findCardById($cards, $cardId);

        if (!$card || $card->isMatched() || $card->isFlipped()) {
            return [
                'success' => false,
                'message' => 'Cette carte ne peut pas être retournée'
            ];
        }

        // Vérifier qu'on n'a pas déjà 2 cartes retournées
        if (count($gameState['flippedCards']) >= 2) {
            return [
                'success' => false,
                'message' => 'Vous avez déjà deux cartes retournées'
            ];
        }

        // Retourner la carte
        $card->flip();
        $gameState['flippedCards'][] = $cardId;

        // Si on a 2 cartes retournées, vérifier la correspondance
        if (count($gameState['flippedCards']) === 2) {
            $gameState['moves']++;
            $result = $this->checkMatch($gameState);

            return [
                'success' => true,
                'card' => $card->toArray(),
                'match' => $result,
                'moves' => $gameState['moves'],
                'matchedPairs' => $gameState['matchedPairs'],
                'totalPairs' => $gameState['totalPairs'],
                'gameOver' => $gameState['matchedPairs'] === $gameState['totalPairs']
            ];
        }

        return [
            'success' => true,
            'card' => $card->toArray(),
            'moves' => $gameState['moves'],
            'matchedPairs' => $gameState['matchedPairs'],
            'totalPairs' => $gameState['totalPairs']
        ];
    }

    /**
     * Vérifier si les deux cartes retournées correspondent
     */
    private function checkMatch(array &$gameState): array
    {
        $cards = $gameState['cards'];
        $flippedCardIds = $gameState['flippedCards'];

        $card1 = $this->findCardById($cards, $flippedCardIds[0]);
        $card2 = $this->findCardById($cards, $flippedCardIds[1]);

        if ($card1->getValue() === $card2->getValue()) {
            // Paire trouvée
            $card1->setMatched();
            $card2->setMatched();
            $gameState['matchedPairs']++;
            $gameState['flippedCards'] = [];

            return [
                'isMatch' => true,
                'cardIds' => $flippedCardIds
            ];
        } else {
            // Pas de correspondance
            $card1->unflip();
            $card2->unflip();
            $result = [
                'isMatch' => false,
                'cardIds' => $flippedCardIds
            ];
            $gameState['flippedCards'] = [];

            return $result;
        }
    }

    /**
     * Trouver une carte par son ID
     */
    private function findCardById(array $cards, string $cardId)
    {
        foreach ($cards as $card) {
            if ($card->getId() === $cardId) {
                return $card;
            }
        }
        return null;
    }

    /**
     * Obtenir l'état actuel du jeu pour l'affichage
     */
    public function getGameState(array $gameState): array
    {
        $cardsData = [];
        foreach ($gameState['cards'] as $card) {
            $cardsData[] = $card->toArray();
        }

        return [
            'cards' => $cardsData,
            'moves' => $gameState['moves'],
            'matchedPairs' => $gameState['matchedPairs'],
            'totalPairs' => $gameState['totalPairs'],
            'elapsedTime' => time() - $gameState['startTime']
        ];
    }
}
