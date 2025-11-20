<?php

namespace App\Helpers;

class UrlHelper
{

    /**
     * Rediriger vers une URL
     */
    public static function redirect($url)
    {
        header("Location: " . $url);
        exit();
    }
}