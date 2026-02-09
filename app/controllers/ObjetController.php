<?php

namespace app\controllers;

use app\models\Objet;
use app\models\Category;
use flight\Engine;

/**
 * ObjetController - Gestion des objets (Admin)
 */
class ObjetController
{
    private Engine $app;
    private Objet $objetModel;
    private Category $categoryModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->objetModel = new Objet($app->db());
        $this->categoryModel = new Category($app->db());
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    private function requireAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
            $this->app->redirect('/login');
            exit;
        }
    }

    /**
     * Afficher la liste des objets
     */
    public function index(): void
    {
        $this->requireAuth();

        $objets = $this->objetModel->getAll();
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $this->app->render('admin/objets/index', [
            'objets' => $objets,
            'success_message' => $successMessage,
            'error_message' => $errorMessage,
            'username' => $_SESSION['username'] ?? 'Admin',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Afficher le formulaire d'édition de catégorie pour un objet
     */
    public function editCategorie(int $id): void
    {
        $this->requireAuth();

        $objet = $this->objetModel->findById($id);
        
        if ($objet === null) {
            $_SESSION['error_message'] = 'Objet introuvable.';
            $this->app->redirect('/admin/objets');
            return;
        }

        $categories = $this->categoryModel->getAll();

        $this->app->render('admin/objets/edit-categorie', [
            'objet' => $objet,
            'categories' => $categories,
            'username' => $_SESSION['username'] ?? 'Admin',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Mettre à jour la catégorie d'un objet
     */
    public function updateCategorie(): void
    {
        $this->requireAuth();

        $objetId = (int) ($this->app->request()->data->objet_id ?? 0);
        $categorieId = $this->app->request()->data->categorie_id ?? '';
        
        // Convertir en int ou null
        $categorieId = $categorieId !== '' ? (int) $categorieId : null;

        if ($objetId <= 0) {
            $_SESSION['error_message'] = 'ID d\'objet invalide.';
            $this->app->redirect('/admin/objets');
            return;
        }

        $objet = $this->objetModel->findById($objetId);
        if ($objet === null) {
            $_SESSION['error_message'] = 'Objet introuvable.';
            $this->app->redirect('/admin/objets');
            return;
        }

        // Vérifier que la catégorie existe si elle est spécifiée
        if ($categorieId !== null) {
            $category = $this->categoryModel->findById($categorieId);
            if ($category === null) {
                $_SESSION['error_message'] = 'Catégorie invalide.';
                $this->app->redirect('/admin/objets/' . $objetId . '/categorie');
                return;
            }
        }

        // Mettre à jour
        $this->objetModel->updateCategorie($objetId, $categorieId);
        
        $categoryName = $categorieId !== null ? $this->categoryModel->findById($categorieId)['name'] : 'Aucune';
        $_SESSION['success_message'] = 'Catégorie de l\'objet "' . htmlspecialchars($objet['title']) . '" mise à jour : ' . htmlspecialchars($categoryName);

        $this->app->redirect('/admin/objets');
    }

    /**
     * Afficher le formulaire de création d'un objet
     */
    public function create(): void
    {
        $this->requireAuth();

        $categories = $this->categoryModel->getAll();

        $this->app->render('admin/objets/create', [
            'categories' => $categories,
            'username' => $_SESSION['username'] ?? 'Admin',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Enregistrer un nouvel objet
     */
    public function store(): void
    {
        $this->requireAuth();

        $title = trim($this->app->request()->data->title ?? '');
        $description = trim($this->app->request()->data->description ?? '');
        $idProprietaire = (int) ($this->app->request()->data->id_proprietaire ?? 0);
        $idCategorie = $this->app->request()->data->id_categorie ?? '';
        $prixEstime = $this->app->request()->data->prix_estime ?? '';
        $qtt = (int) ($this->app->request()->data->qtt ?? 1);

        // Validation
        if (empty($title)) {
            $_SESSION['error_message'] = 'Le titre de l\'objet est requis.';
            $this->app->redirect('/admin/objets/create');
            return;
        }

        if ($idProprietaire <= 0) {
            $_SESSION['error_message'] = 'L\'ID du propriétaire est requis.';
            $this->app->redirect('/admin/objets/create');
            return;
        }

        // Convertir les valeurs optionnelles
        $idCategorie = $idCategorie !== '' ? (int) $idCategorie : null;
        $prixEstime = $prixEstime !== '' ? (float) $prixEstime : null;
        $qtt = $qtt > 0 ? $qtt : 1;

        // Créer l'objet
        $objetId = $this->objetModel->create([
            'title' => $title,
            'description' => $description ?: null,
            'id_proprietaire' => $idProprietaire,
            'id_categorie' => $idCategorie,
            'prix_estime' => $prixEstime,
            'qtt' => $qtt
        ]);

        if ($objetId) {
            $_SESSION['success_message'] = 'Objet "' . htmlspecialchars($title) . '" créé avec succès !';
        } else {
            $_SESSION['error_message'] = 'Erreur lors de la création de l\'objet.';
        }

        $this->app->redirect('/admin/objets');
    }
}
