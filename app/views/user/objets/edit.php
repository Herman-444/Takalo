<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un objet - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <h1>Takalo - Modifier un objet</h1>
                <nav class="header-nav">
                    <span class="user-info">Bienvenue, <?= htmlspecialchars($username ?? 'Utilisateur', ENT_QUOTES, 'UTF-8') ?></span>
                    <a href="/accueil/accueil" class="btn btn-secondary">Accueil</a>
                    <a href="/logout" class="btn btn-logout">Déconnexion</a>
                </nav>
            </div>
        </header>

        <main class="dashboard-main">
            <div class="dashboard-content">
                <div class="page-header">
                    <h2>Modifier l'objet</h2>
                    <a href="/user/objets" class="btn btn-secondary">← Retour à mes objets</a>
                </div>

                <?php if (!empty($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['error_message'])): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <!-- Section images existantes (hors du formulaire principal) -->
                <?php if (!empty($images)): ?>
                <div class="form-container" style="margin-bottom: 20px;">
                    <h3>Images actuelles</h3>
                    <div class="images-grid">
                        <?php foreach ($images as $image): ?>
                            <div class="image-item">
                                <img src="/images/<?= htmlspecialchars($image['image_path'], ENT_QUOTES, 'UTF-8') ?>" 
                                     alt="Image de l'objet">
                                <form method="POST" action="/user/objets/image/delete">
                                    <input type="hidden" name="image_id" value="<?= $image['id'] ?>">
                                    <input type="hidden" name="objet_id" value="<?= $objet['id'] ?>">
                                    <button type="submit" class="btn-delete-image" 
                                            onclick="return confirm('Supprimer cette image ?');">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-container">
                    <form method="POST" action="/user/objets/update" class="admin-form" enctype="multipart/form-data">
                        <input type="hidden" name="objet_id" value="<?= htmlspecialchars($objet['id'], ENT_QUOTES, 'UTF-8') ?>">
                        
                        <div class="form-group">
                            <label for="title">Titre de l'objet *</label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="<?= htmlspecialchars($objet['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Ex: Ballon de football, Vélo, Livre..."
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                placeholder="Description détaillée de l'objet..."
                                rows="4"
                            ><?= htmlspecialchars($objet['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_categorie">Catégorie</label>
                                <select id="id_categorie" name="id_categorie" class="form-select">
                                    <option value="">-- Aucune catégorie --</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            <?= ($objet['id_categorie'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="prix_estime">Prix estimé (€)</label>
                                <input 
                                    type="number" 
                                    id="prix_estime" 
                                    name="prix_estime" 
                                    value="<?= htmlspecialchars($objet['prix_estime'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Ex: 25.00"
                                    step="0.01"
                                    min="0"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="qtt">Quantité</label>
                            <input 
                                type="number" 
                                id="qtt" 
                                name="qtt" 
                                value="<?= htmlspecialchars($objet['qtt'] ?? 1, ENT_QUOTES, 'UTF-8') ?>"
                                min="1"
                            >
                        </div>

                        <div class="form-group">
                            <label for="images">Ajouter des images</label>
                            <input 
                                type="file" 
                                id="images" 
                                name="images[]" 
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                multiple
                                class="file-input"
                            >
                            <small class="form-help">Formats acceptés: JPG, PNG, GIF, WebP. Taille max: 5 Mo par image.</small>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            <a href="/user/objets" class="btn btn-secondary">Annuler</a>
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
