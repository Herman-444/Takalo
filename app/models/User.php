<?php

namespace app\models;

use flight\database\PdoWrapper;

/**
 * User Model - Gestion des utilisateurs
 */
class User
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Trouver un utilisateur par son nom d'utilisateur
     * 
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username): ?array
    {
        $statement = $this->db->runQuery(
            'SELECT id, username, password, type, created_at FROM users WHERE username = ?',
            [$username]
        );
        $user = $statement->fetch();
        return $user ?: null;
    }

    /**
     * Trouver un utilisateur par son ID
     * 
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $statement = $this->db->runQuery(
            'SELECT id, username, type, created_at FROM users WHERE id = ?',
            [$id]
        );
        $user = $statement->fetch();
        return $user ?: null;
    }

    /**
     * Vérifier si l'utilisateur est admin
     * 
     * @param array $user
     * @return bool
     */
    public function isAdmin(array $user): bool
    {
        return isset($user['type']) && $user['type'] === 'admin';
    }

    /**
     * Vérifier le mot de passe
     * 
     * @param string $password
     * @param string $storedPassword
     * @return bool
     */
    public function verifyPassword(string $password, string $storedPassword): bool
    {
        return $password === $storedPassword;
    }

    /**
     * Authentifier un utilisateur
     * 
     * @param string $username
     * @param string $password
     * @return array|null Retourne l'utilisateur si authentifié, null sinon
     */
    public function authenticate(string $username, string $password): ?array
    {
        $user = $this->findByUsername($username);
        
        if ($user === null) {
            return null;
        }

        if (!$this->verifyPassword($password, $user['password'])) {
            return null;
        }

        // Ne pas retourner le mot de passe
        unset($user['password']);
        
        return $user;
    }

    /**
     * Authentifier un utilisateur admin
     * 
     * @param string $username
     * @param string $password
     * @return array|null Retourne l'utilisateur admin si authentifié, null sinon
     */
    public function authenticateAdmin(string $username, string $password): ?array
    {
        $user = $this->authenticate($username, $password);
        
        if ($user === null) {
            return null;
        }

        if (!$this->isAdmin($user)) {
            return null;
        }

        return $user;
    }

    public function register(string $username, string $password): bool
    {

        // Insérer le nouvel utilisateur dans la base de données
        $this->db->runQuery(
            'INSERT INTO users (username, password, type, created_at) VALUES (?, ?, ?, NOW())',
            [$username, $password, 'user']
        );

        return true;
    }
    public function getLastInsert(): ?array
    {
        $statement = $this->db->runQuery(
            'SELECT id, username, type, created_at FROM users ORDER BY id DESC LIMIT 1'
        );
        $user = $statement->fetch();
        return $user ?: null;
    }

}
