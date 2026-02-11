<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Échanges - Takalo</title>
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
                <?php if (!empty($objet_filtre)): ?>
                    <div class="page-header">
                        <h2>Historique : <?= htmlspecialchars($objet_filtre['title']) ?></h2>
                        <a href="/admin/echanges" class="btn btn-primary">← Retour à la liste</a>
                    </div>
                <?php else: ?>
                    <h2>Historique des Échanges</h2>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($objet_filtre)): ?>
                <div class="form-container" style="margin-bottom: 30px;">
                    <form action="/rechercherEchange" method="GET" class="admin-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="dateMin">Date minimum</label>
                                <input type="date" id="dateMin" name="dateMin" class="form-input" value="<?= htmlspecialchars($dateMin ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="dateMax">Date maximum</label>
                                <input type="date" id="dateMax" name="dateMax" class="form-input" value="<?= htmlspecialchars($dateMax ?? '') ?>" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                    </form>
                </div>
                <?php endif; ?>

                <?php if (!empty($objets)): ?>
                    <div class="dashboard-cards">
                        <?php foreach ($objets as $objet): ?>
                            <a href="/afficherHistoriqueEchange/<?= htmlspecialchars($objet['id']) ?>" class="card card-link" style="text-decoration: none;">
                                <?php if (!empty($objet['first_image'])): ?>
                                    <img src="<?= htmlspecialchars($objet['first_image']) ?>" alt="" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
                                <?php else: ?>
                                    <div style="width: 100%; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; font-size: 3rem;">📦</div>
                                <?php endif; ?>
                                <h3><?= htmlspecialchars($objet['title'] ?? '') ?></h3>
                                <p><?= htmlspecialchars(substr($objet['description'] ?? '', 0, 80)) ?><?= strlen($objet['description'] ?? '') > 80 ? '...' : '' ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>

                <?php elseif (!empty($echanges)): ?>
                    <?php foreach ($echanges as $e): ?>
                        <div class="card" style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                                <h3 style="color: #667eea; margin: 0;">Échange #<?= htmlspecialchars($e['echange_id'] ?? '') ?></h3>
                                <span class="badge badge-primary"><?= htmlspecialchars($e['status_name'] ?? '') ?></span>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                                <div>
                                    <p style="font-weight: 600; color: #444; margin-bottom: 5px;">📅 Date</p>
                                    <p style="color: #666;"><?= htmlspecialchars($e['echange_created_at'] ?? '') ?></p>
                                </div>
                                
                                <?php if (!empty($e['proprietaire_username'])): ?>
                                <div>
                                    <p style="font-weight: 600; color: #444; margin-bottom: 5px;">👤 Propriétaire</p>
                                    <p style="color: #666;"><?= htmlspecialchars($e['proprietaire_username']) ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($e['demandeur_username'])): ?>
                                <div>
                                    <p style="font-weight: 600; color: #444; margin-bottom: 5px;">🤝 Demandeur</p>
                                    <p style="color: #666;"><?= htmlspecialchars($e['demandeur_username']) ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($e['objet_title'])): ?>
                                <div>
                                    <p style="font-weight: 600; color: #444; margin-bottom: 5px;">📦 Objet</p>
                                    <p style="color: #666;"><?= htmlspecialchars($e['objet_title']) ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($e['objet_prix'])): ?>
                                <div>
                                    <p style="font-weight: 600; color: #444; margin-bottom: 5px;">💰 Prix estimé</p>
                                    <p style="color: #666;"><?= htmlspecialchars($e['objet_prix']) ?> DH</p>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($e['objet_description'])): ?>
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                                <p style="font-weight: 600; color: #444; margin-bottom: 5px;">📝 Description</p>
                                <p style="color: #666;"><?= htmlspecialchars($e['objet_description']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div class="welcome-message">
                        <h3>Aucune donnée disponible</h3>
                        <p>Aucun objet ou historique d'échange n'a été trouvé. <?= empty($objet_filtre) ? 'Utilisez le formulaire de recherche ci-dessus pour filtrer par date.' : '' ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <footer class="dashboard-footer">
            <p>&copy; <?= date('Y') ?> Takalo - Tous droits réservés</p>
        </footer>
    </div>
</body>
</html>