<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-layout">
    <main class="dashboard-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Tableau de bord</h1>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <div class="dashboard-cards">
                <a href="/admin/categories" class="dash-card">
                    <div class="dash-card-icon blue">🏷️</div>
                    <h3>Catégories</h3>
                    <p>Gérer les catégories d'objets</p>
                </a>

                <a href="/admin/objets" class="dash-card">
                    <div class="dash-card-icon green">📦</div>
                    <h3>Objets</h3>
                    <p>Gérer les objets et leurs catégories</p>
                </a>

                <a href="/admin/utilisateurs" class="dash-card">
                    <div class="dash-card-icon orange">👥</div>
                    <h3>Utilisateurs</h3>
                    <p>Gérer les utilisateurs du système</p>
                </a>

                <a href="/admin/echanges" class="dash-card">
                    <div class="dash-card-icon red">🔄</div>
                    <h3>Échanges</h3>
                    <p>Suivre les échanges en cours</p>
                </a>
            </div>

            <div class="welcome-card">
                <h3>Bienvenue dans l'espace d'administration</h3>
                <p>Vous êtes connecté en tant qu'administrateur. Utilisez les cartes ci-dessus pour gérer les différentes sections du site.</p>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>
