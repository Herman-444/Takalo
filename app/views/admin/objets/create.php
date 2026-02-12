<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un objet - Takalo Admin</title>
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
                <a href="/admin/objets">Objets</a>
                <span class="separator">/</span>
                <span class="current">Ajouter</span>
            </div>

            <div class="page-header">
                <h1 class="page-title">Ajouter un objet</h1>
                <a href="/admin/objets" class="btn btn-secondary">← Retour à la liste</a>
            </div>

            <div class="form-container">
                <form method="POST" action="/admin/objets/store" class="admin-form">
                    <div class="form-group">
                        <label for="title">Titre de l'objet *</label>
                        <input type="text" id="title" name="title" placeholder="Ex: Ballon de football, Vélo, Livre..." required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Description détaillée de l'objet..." rows="4"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_proprietaire">ID du propriétaire *</label>
                            <input type="number" id="id_proprietaire" name="id_proprietaire" placeholder="Ex: 3" min="1" required>
                            <small class="form-help">L'ID de l'utilisateur propriétaire de cet objet</small>
                        </div>

                        <div class="form-group">
                            <label for="id_categorie">Catégorie</label>
                            <select id="id_categorie" name="id_categorie" class="form-select">
                                <option value="">-- Aucune catégorie --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="prix_estime">Prix estimé (€)</label>
                            <input type="number" id="prix_estime" name="prix_estime" placeholder="Ex: 25.00" step="0.01" min="0">
                        </div>

                        <div class="form-group">
                            <label for="qtt">Quantité</label>
                            <input type="number" id="qtt" name="qtt" value="1" min="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Date de création</label>
                        <input type="text" value="<?= date('d/m/Y H:i:s') ?>" disabled class="form-input-disabled">
                        <small class="form-help">La date de création est automatiquement définie à maintenant</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Créer l'objet</button>
                        <a href="/admin/objets" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>

</body>
</html>
