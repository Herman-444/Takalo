<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes échanges - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .echanges-container {
            display: grid;
            gap: 30px;
        }
        
        .echanges-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .echanges-section h3 {
            margin: 0 0 20px 0;
            color: #333;
            font-size: 1.3rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .echange-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }
        
        .echange-card:last-child {
            margin-bottom: 0;
        }
        
        .echange-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .echange-info {
            flex: 1;
        }
        
        .echange-info p {
            margin: 5px 0;
            color: #555;
        }
        
        .echange-info strong {
            color: #333;
        }
        
        .echange-date {
            font-size: 0.85rem;
            color: #888;
        }
        
        .echange-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-en-attente {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-accepte {
            background: #d4edda;
            color: #155724;
        }
        
        .status-refuse {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-annulee {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .echange-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .echange-actions form {
            display: inline;
        }
        
        .btn-annuler {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        
        .btn-annuler:hover {
            background: #c82333;
        }
        
        .btn-accepter {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        
        .btn-accepter:hover {
            background: #218838;
        }
        
        .btn-refuser {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        
        .btn-refuser:hover {
            background: #5a6268;
        }
        
        .empty-state-small {
            text-align: center;
            padding: 30px;
            color: #666;
        }
        
        .echange-objets {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 10px 0;
            flex-wrap: wrap;
        }
        
        .echange-objet {
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
        .echange-fleche {
            font-size: 1.5rem;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <h1>Takalo - Mes échanges</h1>
                <nav class="header-nav">
                    <span class="user-info">Bienvenue, <?= htmlspecialchars($username ?? 'Utilisateur', ENT_QUOTES, 'UTF-8') ?></span>
                    <a href="/accueil/accueil" class="btn btn-secondary">Accueil</a>
                    <a href="/user/objets" class="btn btn-secondary">Mes objets</a>
                    <a href="/logout" class="btn btn-logout">Déconnexion</a>
                </nav>
            </div>
        </header>

        <main class="dashboard-main">
            <div class="dashboard-content">
                <div class="page-header">
                    <h2>Gestion de mes échanges</h2>
                </div>

                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
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

        <footer class="dashboard-footer">
            <p>&copy; <?= date('Y') ?> Takalo - Tous droits réservés</p>
        </footer>
    </div>
</body>
</html>
