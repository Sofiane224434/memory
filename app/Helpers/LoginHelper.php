<?php

namespace App\Helpers;

class LoginHelper
{
    public static function redirectToLogin(): void
    {
        header('Location: /login');
        exit();
    }

    public static function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }
}