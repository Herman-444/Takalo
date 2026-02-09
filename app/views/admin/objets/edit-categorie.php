<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier catégorie - <?= htmlspecialchars($objet['title'], ENT_QUOTES, 'UTF-8') ?> - Takalo Admin</title>
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
                    <h2>Modifier la catégorie de l'objet</h2>
                    <a href="/admin/objets" class="btn btn-secondary">← Retour à la liste</a>
                </div>

                <div class="objet-details card">
                    <h3><?= htmlspecialchars($objet['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <?php if (!empty($objet['description'])): ?>
                        <p class="objet-description"><?= htmlspecialchars($objet['description'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    <?php if ($objet['prix_estime']): ?>
                        <p class="objet-price">Prix estimé: <?= number_format($objet['prix_estime'], 2, ',', ' ') ?> €</p>
                    <?php endif; ?>
                    <p class="objet-current-category">
                        Catégorie actuelle: 
                        <?php if ($objet['categorie_name']): ?>
                            <span class="badge badge-primary"><?= htmlspecialchars($objet['categorie_name'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Non catégorisé</span>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="form-container">
                    <form method="POST" action="/admin/objets/categorie/update" class="admin-form">
                        <input type="hidden" name="objet_id" value="<?= htmlspecialchars($objet['id'], ENT_QUOTES, 'UTF-8') ?>">
                        
                        <div class="form-group">
                            <label for="categorie_id">Sélectionner une catégorie</label>
                            <select id="categorie_id" name="categorie_id" class="form-select">
                                <option value="">-- Aucune catégorie --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>" 
                                            <?= $objet['id_categorie'] == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Valider</button>
                            <a href="/admin/objets" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <footer class="dashboard-footer">
            <p>&copy; <?= date('Y') ?> Takalo - Tous droits réservés</p>
        </footer>
    </div>
</body>
</html>
