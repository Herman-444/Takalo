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
                    o.prix_estime, o.qtt, o.created_at AS objet_created_at,
                    c.name AS categorie,
                    (SELECT i.image_path FROM images i WHERE i.id_objet = o.id ORDER BY i.created_at ASC, i.id ASC LIMIT 1) AS first_image
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
                    o.prix_estime, o.qtt, o.created_at AS objet_created_at,
                    c.name AS categorie,
                    (SELECT i.image_path FROM images i WHERE i.id_objet = o.id ORDER BY i.created_at ASC, i.id ASC LIMIT 1) AS first_image
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
     * Alias pour compatibilité : récupérer tous les objets d'un utilisateur
     *
     * @param int $userId
     * @return array
     */
    public function getAllByUserId(int $userId): array
    {
        return $this->getByProprietaire($userId);
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
    
    public function getAllImageByObjetId(int $objetId): array
    {
        $statement = $this->db->runQuery(
            'SELECT id, image_path FROM images WHERE id_objet = ? ORDER BY created_at ASC',
            [$objetId]
        );
        return $statement->fetchAll() ?: [];
    }

    public function getObjetForUser(int $userId): array
    {
        $statement = $this->db->runQuery(
            'SELECT o.id, o.title, o.description, o.id_proprietaire, o.id_categorie,
                    o.prix_estime, o.qtt, o.created_at AS objet_created_at,
                    c.name AS categorie,
                    (SELECT i.image_path FROM images i WHERE i.id_objet = o.id ORDER BY i.created_at ASC, i.id ASC LIMIT 1) AS first_image
             FROM objets o
             LEFT JOIN categories c ON o.id_categorie = c.id
             WHERE o.id_proprietaire = ?',
            [$userId]
        );
        return $statement->fetchAll() ?: [];
    }

    /**
     * Ajouter une image à un objet
     * 
     * @param int $objetId
     * @param string $imagePath
     * @return int|false
     */
    public function addImage(int $objetId, string $imagePath): int|false
    {
        $this->db->runQuery(
            'INSERT INTO images (id_objet, image_path) VALUES (?, ?)',
            [$objetId, $imagePath]
        );
        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupérer une image par son ID
     * 
     * @param int $imageId
     * @return array|null
     */
    public function getImageById(int $imageId): ?array
    {
        $statement = $this->db->runQuery(
            'SELECT id, id_objet, image_path, created_at FROM images WHERE id = ?',
            [$imageId]
        );
        $image = $statement->fetch();
        return $image ?: null;
    }

    /**
     * Supprimer une image par son ID
     * 
     * @param int $imageId
     * @return bool
     */
    public function deleteImageById(int $imageId): bool
    {
        $statement = $this->db->runQuery(
            'DELETE FROM images WHERE id = ?',
            [$imageId]
        );
        return $statement->rowCount() > 0;
    }

    /**
     * Supprimer toutes les images d'un objet
     * 
     * @param int $objetId
     * @return bool
     */
    public function deleteAllImagesByObjetId(int $objetId): bool
    {
        $statement = $this->db->runQuery(
            'DELETE FROM images WHERE id_objet = ?',
            [$objetId]
        );
        return $statement->rowCount() >= 0;
    }

    /**
     * Changer le propriétaire d'un objet
     * 
     * @param int $objetId
     * @param int $newProprietaireId
     * @return bool
     */
    public function changeProprietaire(int $objetId, int $newProprietaireId): bool
    {
        $statement = $this->db->runQuery(
            'UPDATE objets SET id_proprietaire = ? WHERE id = ?',
            [$newProprietaireId, $objetId]
        );
        return $statement->rowCount() > 0;
    }

    /**
     * Mettre à jour la quantité d'un objet
     * 
     * @param int $objetId
     * @param int $newQtt
     * @return bool
     */
    public function updateQuantite(int $objetId, int $newQtt): bool
    {
        $statement = $this->db->runQuery(
            'UPDATE objets SET qtt = ? WHERE id = ?',
            [$newQtt, $objetId]
        );
        return $statement->rowCount() >= 0;
    }

    /**
     * Dupliquer un objet avec un nouveau propriétaire et une quantité spécifique
     * 
     * @param int $objetId L'ID de l'objet à dupliquer
     * @param int $newProprietaireId Le nouveau propriétaire
     * @param int $qtt La quantité pour le nouvel objet
     * @return int|false L'ID du nouvel objet créé
     */
    public function duplicateForNewOwner(int $objetId, int $newProprietaireId, int $qtt): int|false
    {
        $objet = $this->findById($objetId);
        if ($objet === null) {
            return false;
        }

        // Créer le nouvel objet avec les mêmes attributs
        $newObjetId = $this->create([
            'title' => $objet['title'],
            'description' => $objet['description'],
            'id_proprietaire' => $newProprietaireId,
            'id_categorie' => $objet['id_categorie'],
            'prix_estime' => $objet['prix_estime'],
            'qtt' => $qtt
        ]);

        return $newObjetId;
    }

}
