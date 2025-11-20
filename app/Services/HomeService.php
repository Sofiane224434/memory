<?php
namespace App\Services;

class HomeService
{
    public function getHomeData(): array
    {
        return [
            'title' => 'Bienvenue sur le mini-MVCS',
            'message' => 'Architecture MVCS minimaliste'
        ];
    }
}
