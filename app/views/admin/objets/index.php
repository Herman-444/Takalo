<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des objets - Takalo Admin</title>
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
                    <h2>Gestion des objets</h2>
                    <a href="/admin/objets/create" class="btn btn-primary">+ Ajouter un objet</a>
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

                <p class="info-text">Cliquez sur un objet pour modifier sa catégorie.</p>

                <div class="table-container">
                    <?php if (empty($objets)): ?>
                        <div class="empty-state">
                            <p>Aucun objet pour le moment.</p>
                        </div>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Prix estimé</th>
                                    <th>Catégorie</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($objets as $objet): ?>
                                    <tr class="clickable-row" onclick="window.location='/admin/objets/<?= $objet['id'] ?>/categorie'">
                                        <td><?= htmlspecialchars($objet['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($objet['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars(substr($objet['description'] ?? '', 0, 50), ENT_QUOTES, 'UTF-8') ?><?= strlen($objet['description'] ?? '') > 50 ? '...' : '' ?></td>
                                        <td><?= $objet['prix_estime'] ? number_format($objet['prix_estime'], 2, ',', ' ') . ' €' : '-' ?></td>
                                        <td>
                                            <?php if ($objet['categorie']): ?>
                                                <span class="badge badge-primary"><?= htmlspecialchars($objet['categorie_name'], ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Non catégorisé</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="/admin/objets/<?= $objet['id'] ?>/categorie" class="btn btn-small btn-primary">Modifier catégorie</a>
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
