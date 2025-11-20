<div class="auth-container">
    <h1>Connexion</h1>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/login" class="auth-form">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required value="<?= htmlspecialchars($username ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>

    <p class="auth-link">
        Pas encore inscrit ? <a href="/register">S'inscrire</a>
    </p>
</div>