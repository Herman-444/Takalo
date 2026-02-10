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