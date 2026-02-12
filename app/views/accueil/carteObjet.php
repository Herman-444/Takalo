<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Objet - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="product-detail-section">
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
            $title = is_array($objet) ? ($objet['title'] ?? '') : ($objet->title ?? '');
            $description = is_array($objet) ? ($objet['description'] ?? '') : ($objet->description ?? '');
            $prix = is_array($objet) ? ($objet['prix_estime'] ?? '') : ($objet->prix_estime ?? '');
            $first_image = is_array($objet) ? ($objet['first_image'] ?? '') : ($objet->first_image ?? '');
            $categorie = is_array($objet) ? ($objet['categorie'] ?? '') : ($objet->categorie ?? '');
            $objetId = is_array($objet) ? ($objet['id'] ?? '') : ($objet->id ?? '');
            $qtt = is_array($objet) ? ($objet['qtt'] ?? 1) : ($objet->qtt ?? 1);
            ?>

            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="/accueil/accueil">Accueil</a>
                <span class="separator">/</span>
                <span class="current"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></span>
            </div>

            <div class="product-detail-grid">
                <!-- Gallery -->
                <div class="product-gallery">
                    <div class="product-gallery-main">
                        <?php if (!empty($first_image)): ?>
                            <img src="/images/<?= htmlspecialchars($first_image, ENT_QUOTES, 'UTF-8') ?>" 
                                 alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
                        <?php else: ?>
                            <span class="no-image-text">Aucune image</span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($allImage) && count($allImage) > 1): ?>
                        <div class="product-gallery-thumbs">
                            <?php foreach ($allImage as $idx => $img): ?>
                                <?php $path = is_array($img) ? ($img['image_path'] ?? '') : ($img->image_path ?? ''); ?>
                                <?php if (!empty($path)): ?>
                                    <img src="/images/<?= htmlspecialchars($path, ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="Image <?= $idx + 1 ?>"
                                         class="<?= $idx === 0 ? 'active' : '' ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="product-detail-info">
                    <?php if (!empty($categorie)): ?>
                        <span class="badge badge-primary mb-2"><?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
                    <div class="product-detail-price">
                        <?= $prix ? number_format((float)$prix, 2, ',', ' ') . ' €' : 'Prix non défini' ?>
                    </div>
                    <?php if (!empty($description)): ?>
                        <p class="product-detail-description"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>

                    <div class="product-detail-meta">
                        <div class="product-detail-meta-item">
                            <span class="label">Quantité disponible</span>
                            <span class="value"><?= (int)$qtt ?></span>
                        </div>
                        <?php if (!empty($categorie)): ?>
                        <div class="product-detail-meta-item">
                            <span class="label">Catégorie</span>
                            <span class="value"><?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div style="display:flex;gap:12px;flex-wrap:wrap;">
                        <a href="/demandeEchange/<?= urlencode($objetId) ?>?iduser=<?= urlencode($_SESSION['user_id'] ?? '') ?>" class="btn btn-primary btn-lg">
                            🔄 Demander un échange
                        </a>
                        <a href="/accueil/accueil" class="btn btn-secondary btn-lg">← Retour à la liste</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>
