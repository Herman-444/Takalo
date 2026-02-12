<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des objets - Takalo Admin</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="dashboard-layout">
    <main class="dashboard-main">
        <div class="container">
            <div class="breadcrumb">
                <a href="/admin/dashboard">Dashboard</a>
                <span class="separator">/</span>
                <span class="current">Objets</span>
            </div>

            <div class="page-header">
                <h1 class="page-title">Gestion des objets</h1>
            </div>

            <p class="info-text">Les objets sont créés par les utilisateurs. Cliquez sur un objet pour modifier sa catégorie.</p>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

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
                                    <td><strong><?= htmlspecialchars($objet['title'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td><?= htmlspecialchars(substr($objet['description'] ?? '', 0, 50), ENT_QUOTES, 'UTF-8') ?><?= strlen($objet['description'] ?? '') > 50 ? '...' : '' ?></td>
                                    <td><?= $objet['prix_estime'] ? number_format($objet['prix_estime'], 2, ',', ' ') . ' €' : '-' ?></td>
                                    <td>
                                        <?php if (!empty($objet['categorie'])): ?>
                                            <span class="badge badge-primary"><?= htmlspecialchars($objet['categorie'], ENT_QUOTES, 'UTF-8') ?></span>
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
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>

</body>
</html>
