<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des catégories - Takalo Admin</title>
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
                    <h2>Gestion des catégories</h2>
                    <a href="/admin/categories/create" class="btn btn-primary">+ Ajouter une catégorie</a>
                </div>

                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <div class="table-container">
                    <?php if (empty($categories)): ?>
                        <div class="empty-state">
                            <p>Aucune catégorie pour le moment.</p>
                            <a href="/admin/categories/create" class="btn btn-primary">Créer votre première catégorie</a>
                        </div>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <form method="POST" action="/admin/categories/delete" class="inline-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>">
                                                <button type="submit" class="btn btn-danger btn-small">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <footer class="dashboard-footer">
            <p>&copy; <?= date('Y') ?> Takalo - Tous droits réservés</p>
        </footer>
    </div>
</body>
</html>
