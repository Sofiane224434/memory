<?php

namespace App\Models;

use Core\Database;
use PDO;

class GameModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    /**
     * Créer une nouvelle partie
     */
    public function createGame(int $userId, int $pairsCount): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO games (user_id, pairs_count, moves, time_seconds, score, status) 
             VALUES (:user_id, :pairs_count, 0, 0, 0, 'in_progress')"
        );

        $stmt->execute([
            'user_id' => $userId,
            'pairs_count' => $pairsCount
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Mettre à jour une partie
     */
    public function updateGame(int $gameId, int $moves, int $timeSeconds, string $status = 'in_progress'): bool
    {
        // Récupérer le nombre de paires pour calculer le score
        $game = $this->getGameById($gameId);
        if (!$game) {
            return false;
        }

        $pairsCount = (int) $game['pairs_count'];

        // Calculer le score : 
        // - Score de base proportionnel au nombre de paires (200 points par paire)
        // - Pénalité pour les coups (10 points par coup)
        // - Pénalité pour le temps (1 point par seconde)
        // Plus il y a de paires, plus le score potentiel est élevé
        $baseScore = $pairsCount * 200;
        $score = max(0, $baseScore - ($moves * 10) - $timeSeconds);

        $stmt = $this->pdo->prepare(
            "UPDATE games 
             SET moves = :moves, time_seconds = :time_seconds, score = :score, status = :status 
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $gameId,
            'moves' => $moves,
            'time_seconds' => $timeSeconds,
            'score' => $score,
            'status' => $status
        ]);
    }

    /**
     * Récupérer une partie par son ID
     */
    public function getGameById(int $gameId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM games WHERE id = :id");
        $stmt->execute(['id' => $gameId]);

        $game = $stmt->fetch(PDO::FETCH_ASSOC);
        return $game ?: null;
    }

    /**
     * Récupérer toutes les parties d'un utilisateur
     */
    public function getUserGames(int $userId, int $limit = 10): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM games 
             WHERE user_id = :user_id AND status = 'completed'
             ORDER BY created_at DESC 
             LIMIT :limit"
        );

        $stmt->bindValue('user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les meilleurs scores
     */
    public function getTopScores(int $limit = 10): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT g.*, u.username 
             FROM games g
             JOIN users u ON g.user_id = u.id
             WHERE g.status = 'completed'
             ORDER BY g.score DESC, g.time_seconds ASC
             LIMIT :limit"
        );

        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Marquer une partie comme terminée
     */
    public function completeGame(int $gameId, int $moves, int $timeSeconds): bool
    {
        return $this->updateGame($gameId, $moves, $timeSeconds, 'completed');
    }

    /**
     * Marquer une partie comme abandonnée
     */
    public function abandonGame(int $gameId): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE games SET status = 'abandoned' WHERE id = :id"
        );

        return $stmt->execute(['id' => $gameId]);
    }

    /**
     * Marquer toutes les parties en cours d'un utilisateur comme abandonnées
     */
    public function abandonUserInProgressGames(int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE games SET status = 'abandoned' WHERE user_id = :user_id AND status = 'in_progress'"
        );

        return $stmt->execute(['user_id' => $userId]);
    }
}
