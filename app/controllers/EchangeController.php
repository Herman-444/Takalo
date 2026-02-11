<?php 

namespace app\controllers;

use app\models\Echange;
use app\models\Objet;
use flight\Engine;

class EchangeController
{
    private Engine $app;
    private Echange $echangeModel;
    private Objet $objetModel;
    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->echangeModel = new Echange($this->app->db());
        $this->objetModel = new Objet($this->app->db());
    }

    public function getAllObjects(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $objets = $this->objetModel->getAll();

        $this->app->render('admin/historique/echange', [
            'objets' => $objets,
            'username' => $_SESSION['username'] ?? 'Admin',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    public function historiqueEchange(int $idObjet = 0): void
    {
       if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

       $idObjet = (int) $idObjet;
       if ($idObjet <= 0) {
            $this->app->render('admin/historique/echange', [
                'error' => 'Identifiant d\'objet invalide.',
                'username' => $_SESSION['username'] ?? 'Admin',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        // Récupérer les détails de l'objet
        $objet = $this->objetModel->findById($idObjet);
        if ($objet === null) {
            $this->app->render('admin/historique/echange', [
                'error' => 'Objet introuvable.',
                'username' => $_SESSION['username'] ?? 'Admin',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        $echanges = $this->echangeModel->getHistoriqueEchangeByObjetId($idObjet);

        $this->app->render('admin/historique/echange', [
            'echanges' => $echanges,
            'objet_filtre' => $objet,
            'username' => $_SESSION['username'] ?? 'Admin',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    public function rechercherParDate(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $dateMin = $this->app->request()->query->dateMin ?? '';
        $dateMax = $this->app->request()->query->dateMax ?? '';

        if (empty($dateMin) || empty($dateMax)) {
            $this->app->render('admin/historique/echange', [
                'error' => 'Les dates minimum et maximum sont requises.',
                'username' => $_SESSION['username'] ?? 'Admin',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        // Validation du format de date
        $dateMinObj = \DateTime::createFromFormat('Y-m-d', $dateMin);
        $dateMaxObj = \DateTime::createFromFormat('Y-m-d', $dateMax);

        if (!$dateMinObj || !$dateMaxObj) {
            $this->app->render('admin/historique/echange', [
                'error' => 'Format de date invalide. Utilisez YYYY-MM-DD.',
                'username' => $_SESSION['username'] ?? 'Admin',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        if ($dateMinObj > $dateMaxObj) {
            $this->app->render('admin/historique/echange', [
                'error' => 'La date minimum doit être inférieure ou égale à la date maximum.',
                'username' => $_SESSION['username'] ?? 'Admin',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        $echanges = $this->echangeModel->getEchangesByDateRange($dateMin, $dateMax);

        $this->app->render('admin/historique/echange', [
            'echanges' => $echanges,
            'dateMin' => $dateMin,
            'dateMax' => $dateMax,
            'username' => $_SESSION['username'] ?? 'Admin',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

}