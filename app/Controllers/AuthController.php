<?php

namespace App\Controllers;

use App\Services\AuthService;
use Core\BaseController;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function showRegister(): void
    {
        $this->render('auth/register');
    }

    public function showLogin(): void
    {
        $this->render('auth/login');
    }

    public function register(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($password !== $confirmPassword) {
            $this->render('auth/register', ['error' => 'Les mots de passe ne correspondent pas.', 'username' => $username]);
            return;
        }

        $result = $this->authService->register($username, $password);

        if ($result['success']) {
            $_SESSION['success'] = 'Inscription réussie. Vous pouvez maintenant vous connecter.';
            header('Location: /login');
            exit;
        } else {
            $this->render('auth/register', ['error' => $result['message'], 'username' => $username]);
        }
    }

    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->authService->login($username, $password);

        if ($result['success']) {
            $_SESSION['user'] = $result['user'];
            header('Location: /');
            exit;
        } else {
            $this->render('auth/login', ['error' => $result['message'], 'username' => $username]);
        }
    }

    public function logout(): void
    {
        // Détruire toutes les données de session
        $_SESSION = [];

        // Détruire le cookie de session si existant
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Détruire la session
        session_destroy();

        // Rediriger vers la page d'accueil
        header('Location: /');
        exit;
    }

}