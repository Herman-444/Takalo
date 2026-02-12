<?php 

namespace app\controllers;
use app\models\User;

use flight\Engine;

class UserController
{
    private User $userModel;
    private Engine $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->userModel = new User($app->db());
    }

    public function showLoginForm(): void
    {
        $this->app->render('user/Login/login', [
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    public function showRegistrationForm(): void
    {
        $this->app->render('user/Login/inscription', [
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    public function login(): void
    {
        $username = trim($this->app->request()->data->username ?? '');
        $password = $this->app->request()->data->password ?? '';

        // Validation des entrées
        if (empty($username) || empty($password)) {
            $this->app->render('user/Login/login', [
                'error' => 'Veuillez remplir tous les champs.',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        // Sanitize input
        $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);

        // Authentifier l'utilisateur 
        $user = $this->userModel->authenticate($username, $password);

        if ($user === null) {
            $this->app->render('user/Login/login', [
                'error' => 'Identifiants invalides',
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
        $this->app->redirect('/accueil/accueil/idUser=' . $user['id']);
    }

    public function register(): void
    {
        $username = trim($this->app->request()->data->username ?? '');
        $password = $this->app->request()->data->password ?? '';
        $confirmPassword = $this->app->request()->data->confirm_password ?? '';

        // Validation des entrées
        if (empty($username) || empty($password) || empty($confirmPassword)) {
            $this->app->render('user/Login/inscription', [
                'error' => 'Veuillez remplir tous les champs.',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        if ($password !== $confirmPassword) {
            $this->app->render('user/Login/inscription', [
                'error' => 'Les mots de passe ne correspondent pas.',
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
            return;
        }

        // Sanitize input
        $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);

        // Enregistrer l'utilisateur
        try {
            $this->userModel->register($username, $password);
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
                }
                
                // Régénérer l'ID de session pour éviter la fixation de session
                session_regenerate_id(true);
                
                $user = $this->userModel->getLastInsert();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['type'];
                $_SESSION['logged_in'] = true;
                $_SESSION['success_message'] = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';

        $this->app->redirect('/accueil/accueil');

        } catch (\Exception $e) {
            $this->app->render('user/Login/inscription', [
                'error' => 'Erreur lors de l\'inscription : ' . $e->getMessage(),
                'csp_nonce' => $this->app->get('csp_nonce')
            ]);
        }

    }

    public function AllUtilisateurs(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $users = $this->userModel->getAllUsers();
        $nbrUsers = count($users);

        $this->app->render('admin/utilisateur/utilisateurs', [
            'users' => $users,
            'nbrUsers' => $nbrUsers,
            'username' => $_SESSION['username'] ?? 'Admin',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

}

?>