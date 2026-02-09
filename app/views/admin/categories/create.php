<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une catégorie - Takalo Admin</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <h1>Takalo - Administration</h1>
                <nav class="header-nav">
                    <span class="user-info">Bienvenue, <?= htmlspecialchars($username ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></span>
                    <a href="/admin/dashboard" class="btn btn-secondary">Dashboard</a>
                    <a href="/logout" class="btn btn-logout">Déconnexion</a>
                </nav>
            </div>
        </header>

        <main class="dashboard-main">
            <div class="dashboard-content">
                <div class="page-header">
                    <h2>Ajouter une catégorie</h2>
                    <a href="/admin/categories" class="btn btn-secondary">← Retour à la liste</a>
                </div>

                <div class="form-container">
                    <form method="POST" action="/admin/categories/store" class="admin-form">
                        <div class="form-group">
                            <label for="name">Nom de la catégorie</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Ex: Sport, Nourriture, Électronique..."
                                required
                                autofocus
                            >
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Créer la catégorie</button>
                            <a href="/admin/categories" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <footer class="dashboard-footer">
            <p>&copy; <?= date('Y') ?> Takalo - Tous droits réservés</p>
        </footer>
    </div>
</body>
</html>
