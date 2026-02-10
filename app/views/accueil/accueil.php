<?php 

session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <h1> Bienvenu dans Takalo </h1>
    
    <a href="/login">Se Loger en tant qu'administrateur</a>

    <?php if (empty($objets)): ?>
        <p>Aucun objet disponible pour le moment.</p> 
    <?php endif; ?>

    <?php foreach ($objets as $objet): 
        // Support both arrays and objects returned by the model
        $first_image = is_array($objet) ? ($objet['first_image'] ?? '') : ($objet->first_image ?? '');
        $title = is_array($objet) ? ($objet['title'] ?? '') : ($objet->title ?? '');
        $prix = is_array($objet) ? ($objet['prix_estime'] ?? '') : ($objet->prix_estime ?? '');
        $categorie = is_array($objet) ? ($objet['categorie'] ?? '') : ($objet->categorie ?? '');
    ?>
    <div>
    <a href="/carteObjet?id=<?php echo htmlspecialchars(is_array($objet) ? ($objet['id'] ?? '') : ($objet->id ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
    <p>
        <?php if (!empty($first_image)): ?>
            <img src="/images/<?php echo htmlspecialchars($first_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
        <?php else: ?>
            <span>Aucune image</span>
        <?php endif; ?>
    </p>
        <p><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?php echo htmlspecialchars($prix, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?php echo htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><a href="/demandeEchange/<?php echo urlencode(is_array($objet) ? ($objet['id'] ?? '') : ($objet->id ?? '')); ?>?iduser=<?php echo urlencode($_SESSION['user_id'] ?? ''); ?>">Demander un échange</a></p>

    </a>
    </div>
    <?php endforeach; ?>

</body>
</html>