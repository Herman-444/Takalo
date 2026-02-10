<?php

namespace app\controllers;

use app\models\Echange;
use app\models\Objet;
use flight\Engine;

/**
 * UserEchangeController - Gestion des échanges par l'utilisateur connecté
 */
class UserEchangeController
{
    private Engine $app;
    private Echange $echangeModel;
    private Objet $objetModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->echangeModel = new Echange($app->db());
        $this->objetModel = new Objet($app->db());
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    private function requireAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['logged_in']) || empty($_SESSION['user_id'])) {
            $this->app->redirect('/user/login');
            exit;
        }
    }

    /**
     * Récupérer l'ID de l'utilisateur connecté
     */
    private function getUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }

    /**
     * Afficher la liste des échanges de l'utilisateur
     */
    public function index(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        
        $echangesEnvoyes = $this->echangeModel->getEchangesEnvoyes($userId);
        $echangesRecus = $this->echangeModel->getEchangesRecus($userId);
        
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $this->app->render('user/echanges/index', [
            'echangesEnvoyes' => $echangesEnvoyes,
            'echangesRecus' => $echangesRecus,
            'success_message' => $successMessage,
            'error_message' => $errorMessage,
            'username' => $_SESSION['username'] ?? 'Utilisateur',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Annuler un échange envoyé (par le demandeur)
     */
    public function annuler(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $echangeId = (int) ($this->app->request()->data->echange_id ?? 0);

        if ($echangeId <= 0) {
            $_SESSION['error_message'] = 'ID d\'échange invalide.';
            $this->app->redirect('/user/echanges');
            return;
        }

        // Vérifier que l'utilisateur est le demandeur et que l'échange est en attente
        if (!$this->echangeModel->canUserModifyEchange($echangeId, $userId, 'demandeur')) {
            $_SESSION['error_message'] = 'Vous ne pouvez pas annuler cet échange.';
            $this->app->redirect('/user/echanges');
            return;
        }

        // Mettre à jour le statut à "Annulée"
        if ($this->echangeModel->updateStatus($echangeId, Echange::STATUS_ANNULEE)) {
            $_SESSION['success_message'] = 'Demande d\'échange annulée avec succès.';
        } else {
            $_SESSION['error_message'] = 'Erreur lors de l\'annulation de l\'échange.';
        }

        $this->app->redirect('/user/echanges');
    }

    /**
     * Accepter un échange reçu (par le propriétaire)
     */
    public function accepter(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $echangeId = (int) ($this->app->request()->data->echange_id ?? 0);

        if ($echangeId <= 0) {
            $_SESSION['error_message'] = 'ID d\'échange invalide.';
            $this->app->redirect('/user/echanges');
            return;
        }

        // Vérifier que l'utilisateur est le propriétaire et que l'échange est en attente
        if (!$this->echangeModel->canUserModifyEchange($echangeId, $userId, 'proprietaire')) {
            $_SESSION['error_message'] = 'Vous ne pouvez pas accepter cet échange.';
            $this->app->redirect('/user/echanges');
            return;
        }

        // Traiter l'échange (transfert de propriété des objets)
        if ($this->echangeModel->processEchangeAccepte($echangeId, $this->objetModel)) {
            $_SESSION['success_message'] = 'Échange accepté avec succès ! Les objets ont été transférés.';
        } else {
            $_SESSION['error_message'] = 'Erreur lors de l\'acceptation de l\'échange.';
        }

        $this->app->redirect('/user/echanges');
    }

    /**
     * Refuser un échange reçu (par le propriétaire)
     */
    public function refuser(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $echangeId = (int) ($this->app->request()->data->echange_id ?? 0);

        if ($echangeId <= 0) {
            $_SESSION['error_message'] = 'ID d\'échange invalide.';
            $this->app->redirect('/user/echanges');
            return;
        }

        // Vérifier que l'utilisateur est le propriétaire et que l'échange est en attente
        if (!$this->echangeModel->canUserModifyEchange($echangeId, $userId, 'proprietaire')) {
            $_SESSION['error_message'] = 'Vous ne pouvez pas refuser cet échange.';
            $this->app->redirect('/user/echanges');
            return;
        }

        // Mettre à jour le statut à "Refusé"
        if ($this->echangeModel->updateStatus($echangeId, Echange::STATUS_REFUSE)) {
            $_SESSION['success_message'] = 'Échange refusé.';
        } else {
            $_SESSION['error_message'] = 'Erreur lors du refus de l\'échange.';
        }

        $this->app->redirect('/user/echanges');
    }
}
