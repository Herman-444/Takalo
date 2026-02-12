<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <a href="/accueil/accueil" class="brand">Takalo</a>
            <p>Espace Administration</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" class="auth-form">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="<?= htmlspecialchars($default_username ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Entrez votre nom d'utilisateur"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    value="<?= htmlspecialchars($default_password ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Entrez votre mot de passe"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Se connecter</button>
        </form>

        <div class="auth-footer">
            <p>Accès réservé aux administrateurs</p>
        </div>
    </div>
</div>

</body>
</html>
