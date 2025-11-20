<?php
/**
 * Vue : Page d'accueil
 * ---------------------
 * Cette vue reçoit une variable $title optionnelle
 * transmise par le HomeController.
 */
?>
<h1>
  <!-- On sécurise le titre avec htmlspecialchars et on définit une valeur par défaut -->
  <?= htmlspecialchars($title ?? 'Accueil', ENT_QUOTES, 'UTF-8') ?>
</h1>

<!-- dire bienvenue à l'utilisateur connecté -->
<?php if (isset($_SESSION['user'])): ?>
  <p>Bienvenue, <?= htmlspecialchars($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8') ?> !</p>
<?php else: ?>
  <p>Bienvenue dans le projet mini-MVCS minimal.</p>
<?php endif; ?>