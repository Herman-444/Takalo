<?php

namespace app\controllers;

use app\models\User;
use flight\Engine;

/**
 * AuthController - Gestion de l'authentification admin
 */
class AuthController
{
    private Engine $app;
    private User $userModel;

    // Valeurs par défaut pour le formulaire de login
    private const DEFAULT_USERNAME = 'admin';
    private const DEFAULT_PASSWORD = 'admin123';

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->userModel = new User($app->db());
    }

    /**
     * Afficher le formulaire de login
     */
    public function showLoginForm(): void
    {
        $error = null;
        $username = self::DEFAULT_USERNAME;
        $password = self::DEFAULT_PASSWORD;

        $this->app->render('auth/login', [
            'error' => $error,
            'default_username' => $username,
            'default_password' => $password,
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Traiter la connexion
     */
    public function login(): void
    {
        $username = trim($this->app->request()->data->username ?? '');
        $password = $this->app->request()->data->password ?? '';

        // Validation des entrées
        if (empty($username) || empty($password)) {
            $this->app->render('auth/login', [
                'error' => 'Veuillez remplir tous les champs.',
                'default_username' => $username,
                'default_password' => '',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        // Sanitize input
        $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);

        // Authentifier l'utilisateur admin
        $user = $this->userModel->authenticateAdmin($username, $password);

        if ($user === null) {
            $this->app->render('auth/login', [
                'error' => 'Identifiants invalides ou accès non autorisé.',
                'default_username' => $username,
                'default_password' => '',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        // Démarrer la session et stocker l'utilisateur
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Régénérer l'ID de session pour éviter la fixation de session
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_type'] = $user['type'];
        $_SESSION['logged_in'] = true;
        $_SESSION['success_message'] = 'Connexion réussie ! Bienvenue ' . $user['username'] . '.';

        // Rediriger vers le dashboard admin
        $this->app->redirect('/admin/dashboard');
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Détruire toutes les données de session
        $_SESSION = [];

        // Détruire le cookie de session
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Détruire la session
        session_destroy();

        // Rediriger vers la page de login
        $this->app->redirect('/login');
    }

    /**
     * Dashboard admin (page protégée)
     */
    public function dashboard(): void
    {
        $this->requireAuth();

        // Récupérer le message de succès et le supprimer de la session
        $successMessage = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);

        $this->app->render('admin/dashboard', [
            'username' => $_SESSION['username'] ?? 'Admin',
            'success_message' => $successMessage,
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Vérifier si l'utilisateur est connecté
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
}
