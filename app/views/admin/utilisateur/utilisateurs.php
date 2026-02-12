<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs - Takalo Admin</title>
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
                <span class="current">Utilisateurs</span>
            </div>

            <div class="page-header">
                <h1 class="page-title">Gestion des utilisateurs</h1>
                <a href="/admin/dashboard" class="btn btn-secondary">← Retour au tableau de bord</a>
            </div>

            <!-- User count card -->
            <div class="dashboard-cards">
                <div class="dash-card">
                    <div class="dash-card-icon blue">👥</div>
                    <h3>Utilisateurs inscrits</h3>
                    <div class="stat-number"><?= htmlspecialchars($nbrUsers, ENT_QUOTES, 'UTF-8') ?></div>
                </div>
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
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>

</body>
</html>
