<?php 

session_start();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Takalo</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        /* Styles spécifiques pour la page d'accueil */
        .accueil-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .accueil-header {
            background: white;
            border-radius: 12px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .accueil-header h1 {
            color: #667eea;
            margin: 0;
            font-size: 2rem;
        }
        
        .accueil-nav {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .accueil-nav a {
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        /* Grille des objets */
        .objets-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .objet-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .objet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .objet-card a {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .objet-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .objet-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .objet-card:hover .objet-image img {
            transform: scale(1.05);
        }
        
        .objet-image .no-image {
            color: #999;
            font-size: 0.9rem;
        }
        
        .objet-content {
            padding: 18px;
        }
        
        .objet-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: #333;
            margin: 0 0 10px 0;
            line-height: 1.3;
        }
        
        .objet-prix {
            font-size: 1.2rem;
            font-weight: 700;
            color: #667eea;
            margin: 0 0 8px 0;
        }
        
        .objet-categorie {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-bottom: 12px;
        }
        
        .objet-action {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #eee;
        }
        
        .btn-echange {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .btn-echange:hover {
            background: #218838;
        }
        
        .empty-state {
            background: white;
            border-radius: 12px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .empty-state p {
            color: #666;
            font-size: 1.1rem;
            margin: 0;
        }
        
        /* Message de succès */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Filtre et recherche */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .filter-section h2 {
            font-size: 1.1rem;
            color: #444;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .categories-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .category-btn {
            padding: 8px 18px;
            border-radius: 25px;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
        }

        .category-btn:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .category-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .search-input {
            flex: 1;
            padding: 12px 18px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        }

        .search-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .filter-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 12px 18px;
            background: #e8eafc;
            border-radius: 8px;
            color: #444;
            font-size: 0.95rem;
        }

        .filter-info a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .filter-info a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .accueil-header {
                flex-direction: column;
                text-align: center;
            }
            
            .objets-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="accueil-container">
        <header class="accueil-header">
            <h1>Bienvenue sur Takalo</h1>
            <nav class="accueil-nav">
                <?php if (!empty($_SESSION['logged_in'])): ?>
                    <a href="/user/objets" class="btn-primary">Mes objets</a>
                    <a href="/user/echanges" class="btn-secondary">Mes échanges</a>
                    <a href="/logout" class="btn-secondary">Déconnexion</a>
                <?php else: ?>
                    <a href="/user/login" class="btn-primary">Se connecter</a>
                    <a href="/user/inscription" class="btn-secondary">S'inscrire</a>
                <?php endif; ?>
                <a href="/login" class="btn-secondary">Admin</a>
            </nav>
        </header>

        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Filtre par catégorie et barre de recherche -->
        <div class="filter-section">
            <h2>Filtrer par catégorie</h2>
            <div class="categories-list">
                <a href="/accueil/accueil" class="category-btn <?= empty($selectedCategorie) ? 'active' : '' ?>">Toutes</a>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): 
                        $catId = is_array($cat) ? ($cat['id'] ?? '') : ($cat->id ?? '');
                        $catName = is_array($cat) ? ($cat['name'] ?? '') : ($cat->name ?? '');
                    ?>
                        <a href="/accueil/accueil?categorie=<?= htmlspecialchars($catId, ENT_QUOTES, 'UTF-8') ?>" 
                           class="category-btn <?= (int)($selectedCategorie ?? 0) === (int)$catId ? 'active' : '' ?>">
                            <?= htmlspecialchars($catName, ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <form class="search-form" method="GET" action="/accueil/accueil">
                <?php if (!empty($selectedCategorie)): ?>
                    <input type="hidden" name="categorie" value="<?= htmlspecialchars($selectedCategorie, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
                <input type="text" name="search" class="search-input" 
                       placeholder="Rechercher un objet<?= !empty($selectedCategorie) ? ' dans cette catégorie' : '' ?>..." 
                       value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="search-btn">Rechercher</button>
            </form>
        </div>

        <?php if (!empty($selectedCategorie) || !empty($search)): ?>
            <div class="filter-info">
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

        <?php if (empty($objets)): ?>
            <div class="empty-state">
                <p>Aucun objet disponible pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="objets-list">
                <?php foreach ($objets as $objet): 
                    $first_image = is_array($objet) ? ($objet['first_image'] ?? '') : ($objet->first_image ?? '');
                    $title = is_array($objet) ? ($objet['title'] ?? '') : ($objet->title ?? '');
                    $prix = is_array($objet) ? ($objet['prix_estime'] ?? '') : ($objet->prix_estime ?? '');
                    $categorie = is_array($objet) ? ($objet['categorie'] ?? '') : ($objet->categorie ?? '');
                    $objetId = is_array($objet) ? ($objet['id'] ?? '') : ($objet->id ?? '');
                ?>
                <div class="objet-card">
                    <a href="/carteObjet?id=<?= htmlspecialchars($objetId, ENT_QUOTES, 'UTF-8') ?>">
                        <div class="objet-image">
                            <?php if (!empty($first_image)): ?>
                                <img src="/images/<?= htmlspecialchars($first_image, ENT_QUOTES, 'UTF-8') ?>" 
                                     alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
                            <?php else: ?>
                                <span class="no-image">Aucune image</span>
                            <?php endif; ?>
                        </div>
                        <div class="objet-content">
                            <h3 class="objet-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="objet-prix">
                                <?= $prix ? number_format((float)$prix, 2, ',', ' ') . ' €' : 'Prix non défini' ?>
                            </p>
                            <?php if (!empty($categorie)): ?>
                                <span class="objet-categorie"><?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="objet-content objet-action">
                        <a href="/demandeEchange/<?= urlencode($objetId) ?>?iduser=<?= urlencode($_SESSION['user_id'] ?? '') ?>" 
                           class="btn-echange">Demander un échange</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>