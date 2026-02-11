<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <h1>Takalo - Administration</h1>
                <nav class="header-nav">
                    <span class="user-info">Bienvenue, <?= htmlspecialchars($username ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></span>
                    <a href="/logout" class="btn btn-logout">Déconnexion</a>
                </nav>
            </div>
        </header>

        <main class="dashboard-main">
            <div class="dashboard-content">
                <h2>Tableau de bord</h2>
                
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                
                <div class="dashboard-cards">
                    <a href="/admin/categories" class="card card-link">
                        <div class="card-icon">🏷️</div>
                        <h3>Catégories</h3>
                        <p>Gérer les catégories d'objets</p>
                    </a>
                    
                    <a href="/admin/objets" class="card card-link">
                        <div class="card-icon">📦</div>
                        <h3>Objets</h3>
                        <p>Gérer les objets et leurs catégories</p>
                    </a>
                    
                        <a href="/admin/utilisateurs" class="card card-link">
                        <div class="card-icon">👥</div>
                        <h3>Utilisateurs</h3>
                        <p>Gérer les utilisateurs du système</p>
                    </a>
                    
                    <a href="/admin/echanges" class="card card-link">
                        <div class="card-icon">🔄</div>
                        <h3>Échanges</h3>
                        <p>Suivre les échanges en cours</p>
                    </a>
                </div>

                <div class="welcome-message">
                    <h3>Bienvenue dans l'espace d'administration</h3>
                    <p>Vous êtes connecté en tant qu'administrateur. Utilisez le menu ci-dessus pour gérer les différentes sections du site.</p>
                </div>
            </div>
        </main>

        <footer class="dashboard-footer">
            <p>&copy; <?= date('Y') ?> Takalo - Tous droits réservés</p>
        </footer>
    </div>
</body>
</html>
