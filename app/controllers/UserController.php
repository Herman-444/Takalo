<?php 

namespace app\controllers;

use flight\Engine;

class UserController
{
    private Engine $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
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
}

?>