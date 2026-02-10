<?php

namespace app\models;

use flight\database\PdoWrapper;

/**
 * Echange Model - Gestion des échanges (echangeMere et echangeFille)
 */
class Echange
{
    private PdoWrapper $db;

    // Statuts par défaut (à ajuster selon vos données en base)
    public const STATUS_EN_ATTENTE = 1;
    public const STATUS_ACCEPTE = 2;
    public const STATUS_REFUSE = 3;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Créer un nouvel échange (echangeMere + echangeFille)
     *
     * @param int $idProprietaire ID du propriétaire de l'objet cible
     * @param int $idDemandeur ID de l'utilisateur qui demande l'échange
     * @param int $targetObjetId ID de l'objet cible (celui demandé)
     * @param int $offeredObjetId ID de l'objet proposé par le demandeur
     * @param int $offeredQty Quantité proposée
     * @return int|false ID de l'échange mère créé, ou false en cas d'échec
     */
    public function createEchange(
        int $idProprietaire,
        int $idDemandeur,
        int $targetObjetId,
        int $offeredObjetId,
        int $offeredQty = 1
    ): int|false {
        try {
            // Démarrer une transaction
            $this->db->exec('START TRANSACTION');

            // 1. Insérer dans echangeMere
            $this->db->runQuery(
                'INSERT INTO echangeMere (id_proprietaire, id_demandeur, status_id) VALUES (?, ?, ?)',
                [$idProprietaire, $idDemandeur, self::STATUS_EN_ATTENTE]
            );
            $echangeMereId = (int) $this->db->lastInsertId();

            if ($echangeMereId <= 0) {
                $this->db->exec('ROLLBACK');
                return false;
            }

            // 2. Insérer l'objet cible dans echangeFille (appartient au propriétaire)
            $this->db->runQuery(
                'INSERT INTO echangeFille (id_echangeMere, id_objet, id_proprietaire, qtt) VALUES (?, ?, ?, ?)',
                [$echangeMereId, $targetObjetId, $idProprietaire, 1]
            );

            // 3. Insérer l'objet proposé dans echangeFille (appartient au demandeur)
            $this->db->runQuery(
                'INSERT INTO echangeFille (id_echangeMere, id_objet, id_proprietaire, qtt) VALUES (?, ?, ?, ?)',
                [$echangeMereId, $offeredObjetId, $idDemandeur, $offeredQty]
            );

            // Valider la transaction
            $this->db->exec('COMMIT');

            return $echangeMereId;

        } catch (\Exception $e) {
            $this->db->exec('ROLLBACK');
            error_log('Erreur création échange: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer un échange par son ID
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $statement = $this->db->runQuery(
            'SELECT em.*, s.name AS status_name
             FROM echangeMere em
             LEFT JOIN status s ON em.status_id = s.id
             WHERE em.id = ?',
            [$id]
        );
        $echange = $statement->fetch();
        return $echange ?: null;
    }

    /**
     * Récupérer les objets (filles) d'un échange
     *
     * @param int $echangeMereId
     * @return array
     */
    public function getEchangeFilles(int $echangeMereId): array
    {
        $statement = $this->db->runQuery(
            'SELECT ef.*, o.title AS objet_title
             FROM echangeFille ef
             LEFT JOIN objets o ON ef.id_objet = o.id
             WHERE ef.id_echangeMere = ?',
            [$echangeMereId]
        );
        return $statement->fetchAll() ?: [];
    }

    /**
     * Récupérer les échanges d'un utilisateur (en tant que demandeur ou propriétaire)
     *
     * @param int $userId
     * @return array
     */
    public function getByUserId(int $userId): array
    {
        $statement = $this->db->runQuery(
            'SELECT em.*, s.name AS status_name
             FROM echangeMere em
             LEFT JOIN status s ON em.status_id = s.id
             WHERE em.id_demandeur = ? OR em.id_proprietaire = ?
             ORDER BY em.created_at DESC',
            [$userId, $userId]
        );
        return $statement->fetchAll() ?: [];
    }

    /**
     * Mettre à jour le statut d'un échange
     *
     * @param int $echangeId
     * @param int $statusId
     * @return bool
     */
    public function updateStatus(int $echangeId, int $statusId): bool
    {
        $acceptedAt = ($statusId === self::STATUS_ACCEPTE) ? 'NOW()' : 'NULL';
        
        $statement = $this->db->runQuery(
            "UPDATE echangeMere SET status_id = ?, accepted_at = {$acceptedAt} WHERE id = ?",
            [$statusId, $echangeId]
        );
        return $statement->rowCount() > 0;
    }
}
