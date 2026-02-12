<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des catégories - Takalo Admin</title>
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
                <span class="current">Catégories</span>
            </div>

            <div class="page-header">
                <h1 class="page-title">Gestion des catégories</h1>
                <a href="/admin/categories/create" class="btn btn-primary">+ Ajouter une catégorie</a>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <div class="table-container">
                <?php if (empty($categories)): ?>
                    <div class="empty-state">
                        <p>Aucune catégorie pour le moment.</p>
                        <a href="/admin/categories/create" class="btn btn-primary mt-2">Créer votre première catégorie</a>
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
                                    <td><strong><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
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
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>

</body>
</html>
