<?php

namespace App\Services;

class ErrorService
{
    public function getErrorMessage(int $code): string
    {
        $messages = [
            404 => 'Page non trouvée',
            // Ajoutez d\'autres codes d\'erreur et messages si nécessaire
        ];

        return $messages[$code] ?? 'Erreur inconnue';
    }
}