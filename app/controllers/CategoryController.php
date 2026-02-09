<?php

namespace app\controllers;

use app\models\Category;
use flight\Engine;

/**
 * CategoryController - Gestion des catégories (Admin)
 */
class CategoryController
{
    private Engine $app;
    private Category $categoryModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
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
     * Afficher la liste des catégories
     */
    public function index(): void
    {
        $this->requireAuth();

        $categories = $this->categoryModel->getAll();
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $this->app->render('admin/categories/index', [
            'categories' => $categories,
            'success_message' => $successMessage,
            'error_message' => $errorMessage,
            'username' => $_SESSION['username'] ?? 'Admin',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Afficher le formulaire d'ajout de catégorie
     */
    public function create(): void
    {
        $this->requireAuth();

        $this->app->render('admin/categories/create', [
            'username' => $_SESSION['username'] ?? 'Admin',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(): void
    {
        $this->requireAuth();

        $name = trim($this->app->request()->data->name ?? '');

        // Validation
        if (empty($name)) {
            $_SESSION['error_message'] = 'Le nom de la catégorie est requis.';
            $this->app->redirect('/admin/categories/create');
            return;
        }

        // Vérifier si la catégorie existe déjà
        $existing = $this->categoryModel->findByName($name);
        if ($existing !== null) {
            $_SESSION['error_message'] = 'Cette catégorie existe déjà.';
            $this->app->redirect('/admin/categories/create');
            return;
        }

        // Créer la catégorie
        $categoryId = $this->categoryModel->create($name);

        if ($categoryId) {
            $_SESSION['success_message'] = 'Catégorie "' . htmlspecialchars($name) . '" créée avec succès !';
        } else {
            $_SESSION['error_message'] = 'Erreur lors de la création de la catégorie.';
        }

        $this->app->redirect('/admin/categories');
    }

    /**
     * Supprimer une catégorie
     */
    public function delete(): void
    {
        $this->requireAuth();

        $id = (int) ($this->app->request()->data->id ?? 0);

        if ($id <= 0) {
            $_SESSION['error_message'] = 'ID de catégorie invalide.';
            $this->app->redirect('/admin/categories');
            return;
        }

        $category = $this->categoryModel->findById($id);
        if ($category === null) {
            $_SESSION['error_message'] = 'Catégorie introuvable.';
            $this->app->redirect('/admin/categories');
            return;
        }

        if ($this->categoryModel->delete($id)) {
            $_SESSION['success_message'] = 'Catégorie supprimée avec succès !';
        } else {
            $_SESSION['error_message'] = 'Erreur lors de la suppression.';
        }

        $this->app->redirect('/admin/categories');
    }
}
