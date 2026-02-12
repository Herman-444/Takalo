<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Échanges - Takalo Admin</title>
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
                <span class="current">Échanges</span>
            </div>
            
            <?php if (!empty($objet_filtre)): ?>
                <div class="page-header">
                    <h1 class="page-title">Historique : <?= htmlspecialchars($objet_filtre['title']) ?></h1>
                    <a href="/admin/echanges" class="btn btn-secondary">← Retour à la liste</a>
                </div>
            <?php else: ?>
                <div class="page-header">
                    <h1 class="page-title">Historique des Échanges</h1>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            
            
            <!-- Date search form -->
            <?php if (empty($objet_filtre)): ?>
                
                <div class="dashboard-cards">
                    <div class="dash-card">
                        <div class="dash-card-icon blue">📤</div>
                        <h3>Total Echange Effectuer (accepte) </h3>
                        <div class="stat-number"><?= htmlspecialchars($nbrEchange, ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                </div>
                <div class="form-container mb-3">
                    <form action="/rechercherEchange" method="GET" class="admin-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dateMin">Date minimum</label>
                            <input type="date" id="dateMin" name="dateMin" value="<?= htmlspecialchars($dateMin ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="dateMax">Date maximum</label>
                            <input type="date" id="dateMax" name="dateMax" value="<?= htmlspecialchars($dateMax ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($objets)): ?>

                <!-- Objects grid -->
                <div class="dashboard-cards">
                    <?php foreach ($objets as $objet): ?>
                        <a href="/afficherHistoriqueEchange/<?= htmlspecialchars($objet['id']) ?>" class="dash-card">
                            <?php if (!empty($objet['first_image'])): ?>
                                <img src="/images/<?= htmlspecialchars($objet['first_image']) ?>" alt="" style="width:100%;height:150px;object-fit:cover;border-radius:var(--radius);margin-bottom:12px;">
                            <?php else: ?>
                                <div class="dash-card-icon blue" style="width:100%;height:150px;border-radius:var(--radius);margin-bottom:12px;display:flex;align-items:center;justify-content:center;font-size:3rem;">📦</div>
                            <?php endif; ?>
                            <h3><?= htmlspecialchars($objet['title'] ?? '') ?></h3>
                            <p><?= htmlspecialchars(substr($objet['description'] ?? '', 0, 80)) ?><?= strlen($objet['description'] ?? '') > 80 ? '...' : '' ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>

            <?php elseif (!empty($echanges)): ?>
                <!-- Exchange history list -->
                <?php foreach ($echanges as $e): ?>
                    <div class="echanges-section mb-2">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                            <h3 style="border:none;padding:0;margin:0;">Échange #<?= htmlspecialchars($e['echange_id'] ?? '') ?></h3>
                            <span class="badge badge-primary"><?= htmlspecialchars($e['status_name'] ?? '') ?></span>
                        </div>

                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                            <div>
                                <p class="text-muted" style="font-size:12px;margin-bottom:4px;">📅 Date</p>
                                <p style="font-weight:600;"><?= htmlspecialchars($e['echange_created_at'] ?? '') ?></p>
                            </div>

                            <?php if (!empty($e['proprietaire_username'])): ?>
                            <div>
                                <p class="text-muted" style="font-size:12px;margin-bottom:4px;">👤 Propriétaire</p>
                                <p style="font-weight:600;"><?= htmlspecialchars($e['proprietaire_username']) ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($e['demandeur_username'])): ?>
                            <div>
                                <p class="text-muted" style="font-size:12px;margin-bottom:4px;">🤝 Demandeur</p>
                                <p style="font-weight:600;"><?= htmlspecialchars($e['demandeur_username']) ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($e['objet_title'])): ?>
                            <div>
                                <p class="text-muted" style="font-size:12px;margin-bottom:4px;">📦 Objet</p>
                                <p style="font-weight:600;"><?= htmlspecialchars($e['objet_title']) ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($e['objet_prix'])): ?>
                            <div>
                                <p class="text-muted" style="font-size:12px;margin-bottom:4px;">💰 Prix estimé</p>
                                <p style="font-weight:600;"><?= htmlspecialchars($e['objet_prix']) ?> DH</p>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($e['objet_description'])): ?>
                        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--gray-200);">
                            <p class="text-muted" style="font-size:12px;margin-bottom:4px;">📝 Description</p>
                            <p><?= htmlspecialchars($e['objet_description']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="welcome-card">
                    <h3>Aucune donnée disponible</h3>
                    <p>Aucun objet ou historique d'échange n'a été trouvé. <?= empty($objet_filtre) ? 'Utilisez le formulaire de recherche ci-dessus pour filtrer par date.' : '' ?></p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>

</body>
</html>
