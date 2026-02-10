<?php 

namespace app\controllers;

use app\models\Echange;
use app\models\Objet;
use flight\Engine;

class AccueilController
{
    private Engine $app;
    private Objet $objetModel;
    private Echange $echangeModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->objetModel = new Objet($this->app->db());
        $this->echangeModel = new Echange($this->app->db());
    }

    public function getAllObject(): void
    {
        $objets = $this->objetModel->getAll();

        if (empty($objets)) {
            $this->app->render('accueil/accueil', [
                'objets' => [],
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

         $this->app->render('accueil/accueil', [
            'objets' => $objets,
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);

    }

public function showCarteObjet(string $id = ''): void
{
    // Convertir l'id en entier (peut venir du path @id ou du query string)
    $id = (int) ($id ?: ($this->app->request()->query->id ?? 0));
    error_log('showCarteObjet called with id=' . var_export($id, true));

    if ($id <= 0) {
        // Render a friendly error in the view instead of halting
        $this->app->render('accueil/carteObjet', [
            'error' => 'Identifiant d\'objet invalide.',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
        return;
    }

    $objet = $this->objetModel->findById($id);
    if (!$objet) {
        $this->app->render('accueil/carteObjet', [
            'error' => 'Objet introuvable.',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
        return;
    }

    $allImage = $this->objetModel->getAllImageByObjetId($id);

    $this->app->render('accueil/carteObjet', [
        'objet' => $objet,
        'allImage' => $allImage,
        'csp_nonce' => $this->app->get('csp_nonce')
    ]);
} 

public function showDemandeEchangeForm(string $id = ''): void {

    // Convertir l'id en entier
    $id = (int) $id;
    $idUser = (int) ($this->app->request()->query->iduser ?? 0);

    // Debug
    error_log("showDemandeEchangeForm: id=$id, idUser=$idUser");

    $objetuser = $this->objetModel->getAllByUserId($idUser);

    $objet = $this->objetModel->findById($id);

     if (!$objetuser) {
         $this->app->render('accueil/demandeEchange', [
             'error' => 'vous n\'avez pas d\'objet à échanger.',
             'csp_nonce' => $this->app->get('csp_nonce')
         ]);
         return;
    }

    $this->app->render('accueil/demandeEchange', [
        'objet' => $objet,
        'objetuser' => $objetuser,
        'csp_nonce' => $this->app->get('csp_nonce')
    ]);

}

/**
 * Insérer un échange (POST /demandeEchange)
 */
public function insertEchange(): void
{
    // Démarrer la session pour récupérer user_id
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Récupérer les données du formulaire
    $targetObjetId = (int) ($this->app->request()->data->target_objet_id ?? 0);
    $idUser = (int) ($this->app->request()->data->iduser ?? 0);
    $offeredObjetId = (int) ($this->app->request()->data->offered_objet_id ?? 0);

    // Debug - à retirer en production
    error_log("insertEchange: target=$targetObjetId, user=$idUser, offered=$offeredObjetId");

    // Si iduser est 0, essayer de récupérer depuis la session
    if ($idUser <= 0) {
        $idUser = (int) ($_SESSION['user_id'] ?? 0);
    }

    // Récupérer la quantité correspondant à l'objet proposé
    $qtyKey = 'offered_qty_' . $offeredObjetId;
    $offeredQty = (int) ($this->app->request()->data->$qtyKey ?? 1);
    if ($offeredQty < 1) {
        $offeredQty = 1;
    }

    // Récupérer l'objet cible et les objets de l'utilisateur pour les ré-afficher en cas d'erreur
    $objet = $targetObjetId > 0 ? $this->objetModel->findById($targetObjetId) : null;
    $objetuser = $idUser > 0 ? $this->objetModel->getAllByUserId($idUser) : [];

    // Validation des données
    if ($targetObjetId <= 0 || $idUser <= 0 || $offeredObjetId <= 0) {
        $errorMsg = 'Données invalides: ';
        if ($targetObjetId <= 0) $errorMsg .= 'objet cible manquant, ';
        if ($idUser <= 0) $errorMsg .= 'utilisateur non connecté, ';
        if ($offeredObjetId <= 0) $errorMsg .= 'aucun objet sélectionné à proposer';
        
        $this->app->render('accueil/demandeEchange', [
            'error' => trim($errorMsg, ', ') . '.',
            'objet' => $objet,
            'objetuser' => $objetuser,
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
        return;
    }

    // Récupérer l'objet cible pour obtenir le propriétaire
    if (!$objet) {
        $this->app->render('accueil/demandeEchange', [
            'error' => 'Objet cible introuvable.',
            'objet' => $objet,
            'objetuser' => $objetuser,
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
        return;
    }

    $idProprietaire = is_array($objet) 
        ? (int) ($objet['id_proprietaire'] ?? 0) 
        : (int) ($objet->id_proprietaire ?? 0);

    // Vérifier que le demandeur n'est pas le propriétaire
    if ($idProprietaire === $idUser) {
        $this->app->render('accueil/demandeEchange', [
            'error' => 'Vous ne pouvez pas échanger avec vous-même.',
            'objet' => $objet,
            'objetuser' => $objetuser,
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
        return;
    }

    // Créer l'échange
    $echangeId = $this->echangeModel->createEchange(
        $idProprietaire,
        $idUser,
        $targetObjetId,
        $offeredObjetId,
        $offeredQty
    );

    if ($echangeId === false) {
        $this->app->render('accueil/demandeEchange', [
            'error' => 'Erreur lors de la création de l\'échange. Veuillez réessayer.',
            'objet' => $objet,
            'objetuser' => $objetuser,
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
        return;
    }

    // Succès - rediriger vers la page d'accueil avec un message
    $_SESSION['success_message'] = 'Votre demande d\'échange a été envoyée avec succès !';
    
    $this->app->redirect('/accueil/accueil');
}

}