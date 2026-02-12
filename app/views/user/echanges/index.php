<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes échanges - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="dashboard-layout">
    <main class="dashboard-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Gestion de mes échanges</h1>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <div class="echanges-container">
                <!-- Demandes envoyées -->
                <div class="echanges-section">
                    <h3>📤 Mes demandes envoyées</h3>

                    <?php if (empty($echangesEnvoyes)): ?>
                        <div class="empty-state-small">
                            <p>Vous n'avez pas encore envoyé de demande d'échange.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($echangesEnvoyes as $echange): ?>
                            <div class="echange-card">
                                <div class="echange-header">
                                    <div class="echange-info">
                                        <p><strong>Demande à :</strong> <?= htmlspecialchars($echange['proprietaire_username'] ?? 'Utilisateur inconnu', ENT_QUOTES, 'UTF-8') ?></p>
                                        <div class="echange-objets">
                                            <div class="echange-objet">
                                                <small>Vous proposez :</small><br>
                                                <strong><?= htmlspecialchars($echange['objet_propose'] ?? 'Objet inconnu', ENT_QUOTES, 'UTF-8') ?></strong>
                                            </div>
                                            <span class="echange-fleche">⇄</span>
                                            <div class="echange-objet">
                                                <small>Vous demandez :</small><br>
                                                <strong><?= htmlspecialchars($echange['objet_demande'] ?? 'Objet inconnu', ENT_QUOTES, 'UTF-8') ?></strong>
                                            </div>
                                        </div>
                                        <p class="echange-date">Envoyé le <?= date('d/m/Y à H:i', strtotime($echange['created_at'])) ?></p>
                                    </div>
                                    <?php 
                                    $statusClass = match((int)$echange['status_id']) {
                                        1 => 'status-en-attente',
                                        2 => 'status-accepte',
                                        3 => 'status-refuse',
                                        4 => 'status-annulee',
                                        default => ''
                                    };
                                    ?>
                                    <span class="echange-status <?= $statusClass ?>">
                                        <?= htmlspecialchars($echange['status_name'] ?? 'Inconnu', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </div>

                                <?php if ((int)$echange['status_id'] === 1): ?>
                                    <div class="echange-actions">
                                        <form method="POST" action="/user/echanges/annuler" onsubmit="return confirm('Voulez-vous vraiment annuler cette demande ?');">
                                            <input type="hidden" name="echange_id" value="<?= $echange['id'] ?>">
                                            <button type="submit" class="btn-annuler">Annuler la demande</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Demandes reçues -->
                <div class="echanges-section">
                    <h3>📥 Demandes reçues</h3>

                    <?php if (empty($echangesRecus)): ?>
                        <div class="empty-state-small">
                            <p>Vous n'avez pas reçu de demande d'échange.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($echangesRecus as $echange): ?>
                            <div class="echange-card">
                                <div class="echange-header">
                                    <div class="echange-info">
                                        <p><strong>Demande de :</strong> <?= htmlspecialchars($echange['demandeur_username'] ?? 'Utilisateur inconnu', ENT_QUOTES, 'UTF-8') ?></p>
                                        <div class="echange-objets">
                                            <div class="echange-objet">
                                                <small>Il/Elle propose :</small><br>
                                                <strong><?= htmlspecialchars($echange['objet_propose'] ?? 'Objet inconnu', ENT_QUOTES, 'UTF-8') ?></strong>
                                            </div>
                                            <span class="echange-fleche">⇄</span>
                                            <div class="echange-objet">
                                                <small>Contre votre :</small><br>
                                                <strong><?= htmlspecialchars($echange['objet_demande'] ?? 'Objet inconnu', ENT_QUOTES, 'UTF-8') ?></strong>
                                            </div>
                                        </div>
                                        <p class="echange-date">Reçu le <?= date('d/m/Y à H:i', strtotime($echange['created_at'])) ?></p>
                                    </div>
                                    <?php 
                                    $statusClass = match((int)$echange['status_id']) {
                                        1 => 'status-en-attente',
                                        2 => 'status-accepte',
                                        3 => 'status-refuse',
                                        4 => 'status-annulee',
                                        default => ''
                                    };
                                    ?>
                                    <span class="echange-status <?= $statusClass ?>">
                                        <?= htmlspecialchars($echange['status_name'] ?? 'Inconnu', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </div>

                                <?php if ((int)$echange['status_id'] === 1): ?>
                                    <div class="echange-actions">
                                        <form method="POST" action="/user/echanges/accepter" onsubmit="return confirm('Voulez-vous accepter cet échange ?');">
                                            <input type="hidden" name="echange_id" value="<?= $echange['id'] ?>">
                                            <button type="submit" class="btn-accepter">Accepter</button>
                                        </form>
                                        <form method="POST" action="/user/echanges/refuser" onsubmit="return confirm('Voulez-vous refuser cet échange ?');">
                                            <input type="hidden" name="echange_id" value="<?= $echange['id'] ?>">
                                            <button type="submit" class="btn-refuser">Refuser</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>

</body>
</html>
