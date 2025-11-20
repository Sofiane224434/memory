<?php

namespace App\Services;

use App\Models\UserModel;

class AuthService
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function register(string $username, string $password): array
    {
        // Validation
        if (strlen($username) < 3) {
            return ['success' => false, 'message' => "Le nom d'utilisateur doit contenir au moins 3 caractères."];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => "Le mot de passe doit contenir au moins 6 caractères."];
        }

        // Vérifier si l'utilisateur existe déjà
        if ($this->userModel->findByUsername($username)) {
            return ['success' => false, 'message' => "Ce nom d'utilisateur est déjà utilisé."];
        }

        // Hasher le mot de passe
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Créer l'utilisateur
        $userId = $this->userModel->create($username, $passwordHash);

        if ($userId) {
            return ['success' => true, 'userId' => $userId];
        }

        return ['success' => false, 'message' => "Erreur lors de l'inscription."];
    }

    public function login(string $username, string $password): array
    {
        // Trouver l'utilisateur
        $user = $this->userModel->findByUsername($username);

        if (!$user) {
            return ['success' => false, 'message' => "Nom d'utilisateur ou mot de passe incorrect."];
        }

        // Vérifier le mot de passe
        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => "Nom d'utilisateur ou mot de passe incorrect."];
        }

        // Retourner les informations de l'utilisateur (sans le hash du mot de passe)
        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username']
            ]
        ];
    }
}
