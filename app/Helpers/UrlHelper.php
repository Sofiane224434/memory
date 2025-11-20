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

    public static function url($path = '')
    {
        $baseUrl = rtrim($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/\\');
        return $baseUrl . '/' . ltrim($path, '/\\');
    }
}