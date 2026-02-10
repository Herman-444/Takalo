<?php 

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Objet</title>
</head>
<body>
<?php if (!empty($error)): ?>
    <h1><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p><a href="/accueil/accueil">Retour à la liste</a></p>
<?php else: ?>
    <?php
    $title = is_array($objet) ? ($objet['title'] ?? '') : ($objet->title ?? '');
    $description = is_array($objet) ? ($objet['description'] ?? '') : ($objet->description ?? '');
    $prix = is_array($objet) ? ($objet['prix_estime'] ?? '') : ($objet->prix_estime ?? '');
    $first_image = is_array($objet) ? ($objet['first_image'] ?? '') : ($objet->first_image ?? '');
    ?>
    
    <p>
        <?php if (!empty($first_image)): ?>
            <img src="/images/<?php echo htmlspecialchars($first_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
        <?php else: ?>
            <span>Aucune image</span>
        <?php endif; ?>
    </p>

    <h1><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Prix estimé: <?php echo htmlspecialchars($prix, ENT_QUOTES, 'UTF-8'); ?> €</p>

    <div>
        <h2>Images</h2>
        <?php if (!empty($allImage)): ?>
            <?php foreach ($allImage as $img): ?>
                <?php $path = is_array($img) ? ($img['image_path'] ?? '') : ($img->image_path ?? ''); ?>
                <?php if (!empty($path)): ?>
                    <img src="/images/<?php echo htmlspecialchars($path, ENT_QUOTES, 'UTF-8'); ?>" alt="">
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune image pour cet objet.</p>
        <?php endif; ?>
    </div>
    <p><a href="/demandeEchange/<?php echo urlencode(is_array($objet) ? ($objet['id'] ?? '') : ($objet->id ?? '')); ?>?iduser=<?php echo urlencode($_SESSION['user_id'] ?? ''); ?>">Demander un échange</a></p>
    <p><a href="/accueil/accueil">Retour à la liste</a></p>
<?php endif; ?>
</body>
</html>