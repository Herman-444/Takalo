<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs - Takalo</title>
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
                <div class="page-header">
                    <h2>Gestion des utilisateurs</h2>
                    <a href="/admin/dashboard" class="btn btn-primary">← Retour au tableau de bord</a>
                </div>

                <div class="card" style="margin-bottom: 30px; text-align: center;">
                    <div class="card-icon">👥</div>
                    <h3 style="font-size: 2.5rem; color: #667eea; margin: 10px 0;"><?= htmlspecialchars($nbrUsers, ENT_QUOTES, 'UTF-8') ?></h3>
                    <p style="color: #666; font-size: 1.1rem;">Utilisateurs inscrits</p>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom d'utilisateur</th>
                                <th>Type</th>
                                <th>Date d'inscription</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><strong><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td>
                                        <?php if ($user['type'] === 'admin'): ?>
                                            <span class="badge badge-primary">Administrateur</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Utilisateur</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <footer class="dashboard-footer">
            <p>&copy; <?= date('Y') ?> Takalo - Tous droits réservés</p>
        </footer>
    </div>
</body>
</html>