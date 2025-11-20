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
  <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
  <!-- Menu de navigation global -->
  <nav>
    <a href="/">Accueil</a>
    <?php if (LoginHelper::isLogged()): ?>
      <a href="<?= UrlHelper::url('logout') ?>">Se déconnecter</a>
    <?php else: ?>
      <a href="<?= UrlHelper::url('register') ?>">S'inscrire</a>
      <a href="<?= UrlHelper::url('login') ?>">Se connecter</a>
    <?php endif; ?>
  </nav>

  <!-- Contenu principal injecté depuis BaseController -->
  <main>
    <?= $content ?>
  </main>
</body>

</html>