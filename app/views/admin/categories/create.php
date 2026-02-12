<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une catégorie - Takalo Admin</title>
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
                <a href="/admin/categories">Catégories</a>
                <span class="separator">/</span>
                <span class="current">Ajouter</span>
            </div>

            <div class="page-header">
                <h1 class="page-title">Ajouter une catégorie</h1>
                <a href="/admin/categories" class="btn btn-secondary">← Retour à la liste</a>
            </div>

            <div class="form-container">
                <form method="POST" action="/admin/categories/store" class="admin-form">
                    <div class="form-group">
                        <label for="name">Nom de la catégorie</label>
                        <input type="text" id="name" name="name" placeholder="Ex: Sport, Nourriture, Électronique..." required autofocus>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Créer la catégorie</button>
                        <a href="/admin/categories" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>

</body>
</html>
