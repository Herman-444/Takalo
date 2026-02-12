<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande d'échange - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="exchange-page">
    <div class="container">
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="text-center mt-3">
                <a href="/accueil/accueil" class="btn btn-primary">Retour à la liste</a>
            </div>
        <?php else: ?>
            <?php 
            $first_image = is_array($objet) ? ($objet['first_image'] ?? '') : ($objet->first_image ?? '');
            $title = is_array($objet) ? ($objet['title'] ?? '') : ($objet->title ?? '');
            $prix = is_array($objet) ? ($objet['prix_estime'] ?? '') : ($objet->prix_estime ?? '');
            $categorie = is_array($objet) ? ($objet['categorie'] ?? '') : ($objet->categorie ?? '');
            $target_id = is_array($objet) ? ($objet['id'] ?? '') : ($objet->id ?? '');
            ?>

            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="/accueil/accueil">Accueil</a>
                <span class="separator">/</span>
                <a href="/carteObjet?id=<?= htmlspecialchars($target_id, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></a>
                <span class="separator">/</span>
                <span class="current">Demande d'échange</span>
            </div>

            <div class="page-header">
                <h1 class="page-title">Proposer un échange</h1>
            </div>

            <div class="exchange-grid">
                <!-- Target object -->
                <div class="exchange-target-card">
                    <div class="card-image">
                        <?php if (!empty($first_image)): ?>
                            <img src="/images/<?= htmlspecialchars($first_image, ENT_QUOTES, 'UTF-8') ?>" 
                                 alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h2><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
                        <div class="product-card-price mb-1">
                            <?= $prix ? number_format((float)$prix, 2, ',', ' ') . ' €' : 'Prix non défini' ?>
                        </div>
                        <?php if (!empty($categorie)): ?>
                            <span class="badge badge-primary"><?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Exchange form -->
                <div class="exchange-form-card">
                    <h3>🔄 Choisissez un de vos objets à proposer</h3>

                    <form action="/demandeEchange" method="POST">
                        <input type="hidden" name="target_objet_id" value="<?= htmlspecialchars($target_id, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="iduser" value="<?= htmlspecialchars($_SESSION['user_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                        <?php if (!empty($objetuser) && is_iterable($objetuser)): ?>
                            <?php foreach ($objetuser as $objetuse): ?>
                                <?php 
                                $use_id = is_array($objetuse) ? ($objetuse['id'] ?? '') : ($objetuse->id ?? ''); 
                                $use_title = is_array($objetuse) ? ($objetuse['title'] ?? '') : ($objetuse->title ?? '');
                                ?>
                                <label class="radio-card">
                                    <input type="radio" name="offered_objet_id" value="<?= htmlspecialchars($use_id, ENT_QUOTES, 'UTF-8') ?>" required>
                                    <span class="radio-card-content"><?= htmlspecialchars($use_title, ENT_QUOTES, 'UTF-8') ?></span>
                                    <input type="number" name="offered_qty_<?= htmlspecialchars($use_id, ENT_QUOTES, 'UTF-8') ?>" min="1" value="1" style="width:80px;">
                                </label>
                            <?php endforeach; ?>

                            <div class="form-actions mt-3">
                                <button type="submit" class="btn btn-primary">🔄 Envoyer la demande</button>
                                <a href="/accueil/accueil" class="btn btn-secondary">Annuler</a>
                            </div>
                        <?php else: ?>
                            <div class="empty-state-small">
                                <p>Vous n'avez pas d'objets à proposer.</p>
                                <a href="/user/objets/create" class="btn btn-primary mt-2">+ Ajouter un objet</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>
