<div class="auth-container">
    <h1>Inscription</h1>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/register" class="auth-form">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required minlength="3" maxlength="50"
                value="<?= htmlspecialchars($username ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        </div>

        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>

    <p class="auth-link">
        Déjà inscrit ? <a href="/login">Se connecter</a>
    </p>
</div>