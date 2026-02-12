<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge">🔄 Plateforme d'échange</span>
            <h1 class="hero-title">Échangez vos objets <span>simplement</span></h1>
            <p class="hero-description">Trouvez des objets à échanger et proposez les vôtres. Takalo facilite l'échange entre particuliers en toute confiance.</p>
            <div class="hero-actions">
                <?php if (!empty($_SESSION['logged_in'])): ?>
                    <a href="/user/objets/create" class="btn btn-primary btn-lg">+ Publier un objet</a>
                    <a href="/user/echanges" class="btn btn-outline btn-lg">Mes échanges</a>
                <?php else: ?>
                    <a href="/user/inscription" class="btn btn-primary btn-lg">S'inscrire gratuitement</a>
                    <a href="/user/login" class="btn btn-outline btn-lg">Se connecter</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Category Filter -->
<section class="filter-section">
    <div class="container">
        <div class="filter-pills">
            <a href="/accueil/accueil" class="filter-pill <?= empty($selectedCategorie) && empty($search) ? 'active' : '' ?>">Tous</a>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): 
                    $catId = is_array($cat) ? ($cat['id'] ?? '') : ($cat->id ?? '');
                    $catName = is_array($cat) ? ($cat['name'] ?? '') : ($cat->name ?? '');
                ?>
                    <a href="/accueil/accueil?categorie=<?= htmlspecialchars($catId, ENT_QUOTES, 'UTF-8') ?>" 
                       class="filter-pill <?= (int)($selectedCategorie ?? 0) === (int)$catId ? 'active' : '' ?>">
                        <?= htmlspecialchars($catName, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Search bar -->
        <form method="GET" action="/accueil/accueil" style="display:flex;gap:10px;max-width:500px;margin:0 auto;">
            <?php if (!empty($selectedCategorie)): ?>
                <input type="hidden" name="categorie" value="<?= htmlspecialchars($selectedCategorie, ENT_QUOTES, 'UTF-8') ?>">
            <?php endif; ?>
            <div class="navbar-search" style="flex:1;max-width:none;">
                <input type="text" name="search" placeholder="Rechercher un objet..." value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </button>
            </div>
        </form>

        <?php if (!empty($selectedCategorie) || !empty($search)): ?>
            <div class="filter-info-bar" style="margin-top:15px;">
                <span>
                    <?php if (!empty($selectedCategorie)): ?>
                        <?php 
                            $currentCatName = '';
                            if (!empty($categories)) {
                                foreach ($categories as $cat) {
                                    $cId = is_array($cat) ? ($cat['id'] ?? 0) : ($cat->id ?? 0);
                                    if ((int)$cId === (int)$selectedCategorie) {
                                        $currentCatName = is_array($cat) ? ($cat['name'] ?? '') : ($cat->name ?? '');
                                        break;
                                    }
                                }
                            }
                        ?>
                        Catégorie : <strong><?= htmlspecialchars($currentCatName, ENT_QUOTES, 'UTF-8') ?></strong>
                    <?php endif; ?>
                    <?php if (!empty($search)): ?>
                        <?= !empty($selectedCategorie) ? ' — ' : '' ?>Recherche : "<strong><?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?></strong>"
                    <?php endif; ?>
                    (<?= count($objets) ?> résultat<?= count($objets) > 1 ? 's' : '' ?>)
                </span>
                <a href="/accueil/accueil">✕ Effacer les filtres</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Products Grid -->
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Objets des autres Utilisateurs</h2>
        </div>

        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (empty($objets)): ?>
            <div class="empty-state">
                <p>Aucun objet disponible pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($objets as $objet): 
                    $first_image = is_array($objet) ? ($objet['first_image'] ?? '') : ($objet->first_image ?? '');
                    $title = is_array($objet) ? ($objet['title'] ?? '') : ($objet->title ?? '');
                    $prix = is_array($objet) ? ($objet['prix_estime'] ?? '') : ($objet->prix_estime ?? '');
                    $categorie = is_array($objet) ? ($objet['categorie'] ?? '') : ($objet->categorie ?? '');
                    $objetId = is_array($objet) ? ($objet['id'] ?? '') : ($objet->id ?? '');
                    $proprietaire = is_array($objet) ? ($objet['nomProprietaire'] ?? '' ) : ($objet->nomProprietaire ?? '');
                ?>
                <div class="product-card">
                    <div class="product-card-image">
                        <?php if (!empty($first_image)): ?>
                            <img src="/images/<?= htmlspecialchars($first_image, ENT_QUOTES, 'UTF-8') ?>" 
                                 alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
                        <?php else: ?>
                            <span class="no-image">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                Aucune image
                            </span>
                        <?php endif; ?>

                        <?php if (!empty($categorie)): ?>
                            <span class="product-badge product-badge-category"><?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>

                        <!-- Hover overlay -->
                        <div class="product-card-overlay">
                            <a href="/demandeEchange/<?= urlencode($objetId) ?>?iduser=<?= urlencode($_SESSION['user_id'] ?? '') ?>" class="btn-exchange">
                                🔄 Demander Échange
                            </a>
                            <div class="product-card-actions">
                                <a href="/carteObjet?id=<?= htmlspecialchars($objetId, ENT_QUOTES, 'UTF-8') ?>" class="action-btn" title="Voir détails">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="product-card-body">
                        <h3 class="product-card-title">
                            <p> 👤 - <?= $proprietaire ?></p>
                            <a href="/carteObjet?id=<?= htmlspecialchars($objetId, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> 
                            </a>
                        </h3>
                        <div class="product-card-price">
                            <?= $prix ? number_format((float)$prix, 2, ',', ' ') . ' €' : 'Prix non défini' ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>
