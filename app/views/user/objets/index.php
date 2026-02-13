<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes objets - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="dashboard-layout">
    <main class="dashboard-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Mes objets à échanger</h1>
                <a href="/user/objets/create" class="btn btn-primary">+ Ajouter un objet</a>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if (empty($objets)): ?>
                <div class="empty-state">
                    <p>Vous n'avez pas encore d'objets.</p>
                    <a href="/user/objets/create" class="btn btn-primary mt-2">Ajouter mon premier objet</a>
                </div>
            <?php else: ?>
                <div class="objets-grid">
                    <?php foreach ($objets as $objet): ?>
                        <div class="objet-card">
                            <div class="objet-image">
                                <?php if (!empty($objet['first_image'])): ?>
                                    <img src="/images/<?= htmlspecialchars($objet['first_image'], ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="<?= htmlspecialchars($objet['title'], ENT_QUOTES, 'UTF-8') ?>">
                                <?php else: ?>
                                    <span class="no-image-text">Aucune image</span>
                                <?php endif; ?>
                            </div>
                            <div class="objet-info">
                                <h3><?= htmlspecialchars($objet['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <?php if (!empty($objet['categorie'])): ?>
                                    <span class="badge badge-primary"><?= htmlspecialchars($objet['categorie'], ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                                <p class="prix">
                                    <?= $objet['prix_estime'] ? number_format($objet['prix_estime'], 2, ',', ' ') . ' €' : 'Prix non défini' ?>
                                </p>
                                <p class="qtt">Quantité: <?= (int) $objet['qtt'] ?></p>
                                <br></br>
                                <p> <a href="/accueil/accueil?objetId=<?= $objet['id'] ?>&pourcent=10"> +/- 10% </a>    -    <a href="/accueil/accueil?objetId=<?= $objet['id'] ?>&pourcent=20"> +/- 20% </a></p>
                            </div>
                            <div class="objet-actions">
                                <a href="/user/objets/<?= $objet['id'] ?>/edit" class="btn btn-small btn-secondary">Modifier</a>
                                <form method="POST" action="/user/objets/delete" class="delete-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet objet ?');">
                                    <input type="hidden" name="objet_id" value="<?= $objet['id'] ?>">
                                    <button type="submit" class="btn btn-small btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>

</body>
</html>
