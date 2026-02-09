<?php

namespace app\models;

use flight\database\PdoWrapper;

/**
 * Category Model - Gestion des catégories
 */
class Category
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupérer toutes les catégories
     * 
     * @return array
     */
    public function getAll(): array
    {
        $statement = $this->db->runQuery('SELECT id, name FROM categories ORDER BY name ASC');
        return $statement->fetchAll() ?: [];
    }

    /**
     * Trouver une catégorie par son ID
     * 
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $statement = $this->db->runQuery(
            'SELECT id, name FROM categories WHERE id = ?',
            [$id]
        );
        $category = $statement->fetch();
        return $category ?: null;
    }

    /**
     * Trouver une catégorie par son nom
     * 
     * @param string $name
     * @return array|null
     */
    public function findByName(string $name): ?array
    {
        $statement = $this->db->runQuery(
            'SELECT id, name FROM categories WHERE name = ?',
            [$name]
        );
        $category = $statement->fetch();
        return $category ?: null;
    }

    /**
     * Créer une nouvelle catégorie
     * 
     * @param string $name
     * @return int|false L'ID de la catégorie créée ou false en cas d'erreur
     */
    public function create(string $name): int|false
    {
        $this->db->runQuery(
            'INSERT INTO categories (name) VALUES (?)',
            [$name]
        );
        return (int) $this->db->lastInsertId();
    }

    /**
     * Mettre à jour une catégorie
     * 
     * @param int $id
     * @param string $name
     * @return bool
     */
    public function update(int $id, string $name): bool
    {
        $statement = $this->db->runQuery(
            'UPDATE categories SET name = ? WHERE id = ?',
            [$name, $id]
        );
        return $statement->rowCount() > 0;
    }

    /**
     * Supprimer une catégorie
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $statement = $this->db->runQuery(
            'DELETE FROM categories WHERE id = ?',
            [$id]
        );
        return $statement->rowCount() > 0;
    }
}
