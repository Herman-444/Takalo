<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande d'échange</title>
</head>
<body>
    
    <?php if (!empty($error)){ ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>

    <?php } else { ?>

    <?php 
        // Single target object (not iterable)
        $first_image = is_array($objet) ? ($objet['first_image'] ?? '') : ($objet->first_image ?? '');
        $title = is_array($objet) ? ($objet['title'] ?? '') : ($objet->title ?? '');
        $prix = is_array($objet) ? ($objet['prix_estime'] ?? '') : ($objet->prix_estime ?? '');
        $categorie = is_array($objet) ? ($objet['categorie'] ?? '') : ($objet->categorie ?? '');
        $target_id = is_array($objet) ? ($objet['id'] ?? '') : ($objet->id ?? '');
    ?>

    <div class="target-objet">
        <p>
            <?php if (!empty($first_image)): ?>
                <img src="/images/<?php echo htmlspecialchars($first_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
            <?php else: ?>
                <span>Aucune image</span>
            <?php endif; ?>
        </p>
        <h2><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <p><?php echo htmlspecialchars($prix, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?php echo htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <form action="/demandeEchange" method="post">
        <input type="hidden" name="target_objet_id" value="<?php echo htmlspecialchars($target_id, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="iduser" value="<?php echo htmlspecialchars($_SESSION['user_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

        <h3>Choisissez un de vos objets à proposer :</h3>

        <?php if (!empty($objetuser) && is_iterable($objetuser)): ?>
            <?php foreach ($objetuser as $objetuse): ?>
                <?php $use_id = is_array($objetuse) ? ($objetuse['id'] ?? '') : ($objetuse->id ?? ''); ?>
                <?php $use_title = is_array($objetuse) ? ($objetuse['title'] ?? '') : ($objetuse->title ?? ''); ?>
                <div>
                    <label>
                        <input type="radio" name="offered_objet_id" value="<?php echo htmlspecialchars($use_id, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($use_title, ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                    <input type="number" name="offered_qty_<?php echo htmlspecialchars($use_id, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Quantité" min="1" value="1">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez pas d'objets à proposer.</p>
        <?php endif; ?>

        <button type="submit">Envoyer la demande</button>
    </form>

    <?php } ?>

</body>
</html>