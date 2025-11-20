<?php
/**
 * Layout principal
 * -----------------
 * Ce fichier définit la structure HTML commune à toutes les pages.
 * Il inclut dynamiquement le contenu spécifique à chaque vue via la variable $content.
 */
use App\Helpers\UrlHelper;
use App\Helpers\LoginHelper;
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">

  <!-- Titre de la page (sécurisé avec htmlspecialchars, valeur par défaut si non défini) -->
  <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : 'Mini MVCS' ?></title>

  <!-- Bonne pratique : rendre le site responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- (Optionnel) Ajout d’un peu de style basique -->
  <link rel="stylesheet" href="/assets/css/output.css">
</head>

<body class="bg-gray-100 min-h-screen">
  <!-- Menu de navigation global -->
  <nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex space-x-8 items-center">
          <a href="/" class="text-gray-800 hover:text-blue-600 font-medium transition">Accueil</a>
          <?php if (LoginHelper::isLogged()): ?>
            <a href="<?= UrlHelper::url('game') ?>"
              class="text-gray-800 hover:text-blue-600 font-medium transition">Jeu</a>
            <a href="<?= UrlHelper::url('logout') ?>" class="text-gray-800 hover:text-red-600 font-medium transition">Se
              déconnecter</a>
          <?php else: ?>
            <a href="<?= UrlHelper::url('register') ?>"
              class="text-gray-800 hover:text-blue-600 font-medium transition">S'inscrire</a>
            <a href="<?= UrlHelper::url('login') ?>" class="text-gray-800 hover:text-blue-600 font-medium transition">Se
              connecter</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <!-- Contenu principal injecté depuis BaseController -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?= $content ?>
  </main>
</body>

</html>