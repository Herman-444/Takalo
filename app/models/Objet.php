<?php

namespace app\models;

use flight\database\PdoWrapper;

/**
 * Objet Model - Gestion des objets
 */
class Objet
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupérer tous les objets avec leur catégorie
     * 
     * @return array
     */
    public function getAll(): array
    {
        $statement = $this->db->runQuery(
            'SELECT o.id, o.title, o.description, o.id_proprietaire, o.id_categorie, 
                    o.prix_estime, o.qtt, o.created_at, c.name as categorie_name
             FROM objets o
             LEFT JOIN categories c ON o.id_categorie = c.id
             ORDER BY o.created_at DESC'
        );
        return $statement->fetchAll() ?: [];
    }

    /**
     * Trouver un objet par son ID
     * 
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $statement = $this->db->runQuery(
            'SELECT o.id, o.title, o.description, o.id_proprietaire, o.id_categorie, 
                    o.prix_estime, o.qtt, o.created_at, c.name as categorie_name
             FROM objets o
             LEFT JOIN categories c ON o.id_categorie = c.id
             WHERE o.id = ?',
            [$id]
        );
        $objet = $statement->fetch();
        return $objet ?: null;
    }

    /**
     * Récupérer les objets d'un propriétaire
     * 
     * @param int $proprietaireId
     * @return array
     */
    public function getByProprietaire(int $proprietaireId): array
    {
        $statement = $this->db->runQuery(
            'SELECT o.id, o.title, o.description, o.id_proprietaire, o.id_categorie, 
                    o.prix_estime, o.qtt, o.created_at, c.name as categorie_name
             FROM objets o
             LEFT JOIN categories c ON o.id_categorie = c.id
             WHERE o.id_proprietaire = ?
             ORDER BY o.created_at DESC',
            [$proprietaireId]
        );
        return $statement->fetchAll() ?: [];
    }

    /**
     * Mettre à jour la catégorie d'un objet
     * 
     * @param int $objetId
     * @param int|null $categorieId
     * @return bool
     */
    public function updateCategorie(int $objetId, ?int $categorieId): bool
    {
        $statement = $this->db->runQuery(
            'UPDATE objets SET id_categorie = ? WHERE id = ?',
            [$categorieId, $objetId]
        );
        return $statement->rowCount() > 0;
    }

    /**
     * Créer un nouvel objet
     * 
     * @param array $data
     * @return int|false
     */
    public function create(array $data): int|false
    {
        $this->db->runQuery(
            'INSERT INTO objets (title, description, id_proprietaire, id_categorie, prix_estime, qtt) 
             VALUES (?, ?, ?, ?, ?, ?)',
            [
                $data['title'],
                $data['description'] ?? null,
                $data['id_proprietaire'],
                $data['id_categorie'] ?? null,
                $data['prix_estime'] ?? null,
                $data['qtt'] ?? 1
            ]
        );
        return (int) $this->db->lastInsertId();
    }

    /**
     * Mettre à jour un objet
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $statement = $this->db->runQuery(
            'UPDATE objets SET title = ?, description = ?, id_categorie = ?, prix_estime = ?, qtt = ? WHERE id = ?',
            [
                $data['title'],
                $data['description'] ?? null,
                $data['id_categorie'] ?? null,
                $data['prix_estime'] ?? null,
                $data['qtt'] ?? 1,
                $id
            ]
        );
        return $statement->rowCount() > 0;
    }

    /**
     * Supprimer un objet
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $statement = $this->db->runQuery(
            'DELETE FROM objets WHERE id = ?',
            [$id]
        );
        return $statement->rowCount() > 0;
    }
}
