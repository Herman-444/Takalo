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
    public const STATUS_ANNULEE = 4;

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

    /**
     * Récupérer les échanges envoyés par un utilisateur (en tant que demandeur)
     *
     * @param int $userId
     * @return array
     */
    public function getEchangesEnvoyes(int $userId): array
    {
        $statement = $this->db->runQuery(
            'SELECT em.id, em.id_proprietaire, em.id_demandeur, em.status_id, 
                    em.created_at, em.accepted_at,
                    s.name AS status_name,
                    u.username AS proprietaire_username,
                    -- Objet demandé (celui du propriétaire)
                    (SELECT o.title FROM echangeFille ef 
                     JOIN objets o ON ef.id_objet = o.id 
                     WHERE ef.id_echangeMere = em.id AND ef.id_proprietaire = em.id_proprietaire 
                     LIMIT 1) AS objet_demande,
                    -- Objet proposé (celui du demandeur)
                    (SELECT o.title FROM echangeFille ef 
                     JOIN objets o ON ef.id_objet = o.id 
                     WHERE ef.id_echangeMere = em.id AND ef.id_proprietaire = em.id_demandeur 
                     LIMIT 1) AS objet_propose
             FROM echangeMere em
             LEFT JOIN status s ON em.status_id = s.id
             LEFT JOIN users u ON em.id_proprietaire = u.id
             WHERE em.id_demandeur = ?
             ORDER BY em.created_at DESC',
            [$userId]
        );
        return $statement->fetchAll() ?: [];
    }

    /**
     * Récupérer les échanges reçus par un utilisateur (en tant que propriétaire)
     *
     * @param int $userId
     * @return array
     */
    public function getEchangesRecus(int $userId): array
    {
        $statement = $this->db->runQuery(
            'SELECT em.id, em.id_proprietaire, em.id_demandeur, em.status_id, 
                    em.created_at, em.accepted_at,
                    s.name AS status_name,
                    u.username AS demandeur_username,
                    -- Objet demandé (le mien, celui du propriétaire)
                    (SELECT o.title FROM echangeFille ef 
                     JOIN objets o ON ef.id_objet = o.id 
                     WHERE ef.id_echangeMere = em.id AND ef.id_proprietaire = em.id_proprietaire 
                     LIMIT 1) AS objet_demande,
                    -- Objet proposé (celui du demandeur)
                    (SELECT o.title FROM echangeFille ef 
                     JOIN objets o ON ef.id_objet = o.id 
                     WHERE ef.id_echangeMere = em.id AND ef.id_proprietaire = em.id_demandeur 
                     LIMIT 1) AS objet_propose
             FROM echangeMere em
             LEFT JOIN status s ON em.status_id = s.id
             LEFT JOIN users u ON em.id_demandeur = u.id
             WHERE em.id_proprietaire = ?
             ORDER BY em.created_at DESC',
            [$userId]
        );
        return $statement->fetchAll() ?: [];
    }

    /**
     * Vérifier si un utilisateur peut modifier un échange
     *
     * @param int $echangeId
     * @param int $userId
     * @param string $role 'demandeur' ou 'proprietaire'
     * @return bool
     */
    public function canUserModifyEchange(int $echangeId, int $userId, string $role = 'both'): bool
    {
        $echange = $this->findById($echangeId);
        if ($echange === null) {
            return false;
        }

        // Seuls les échanges en attente peuvent être modifiés
        if ((int) $echange['status_id'] !== self::STATUS_EN_ATTENTE) {
            return false;
        }

        if ($role === 'demandeur') {
            return (int) $echange['id_demandeur'] === $userId;
        } elseif ($role === 'proprietaire') {
            return (int) $echange['id_proprietaire'] === $userId;
        }

        return (int) $echange['id_demandeur'] === $userId || (int) $echange['id_proprietaire'] === $userId;
    }

    /**
     * Traiter l'échange lorsqu'il est accepté
     * Gère le transfert de propriété des objets selon les règles:
     * - Si qtt échangée = qtt totale: transfert direct de propriété
     * - Si qtt échangée < qtt totale: création nouvel objet + soustraction quantité
     *
     * @param int $echangeId
     * @param Objet $objetModel
     * @return bool
     */
    public function processEchangeAccepte(int $echangeId, Objet $objetModel): bool
    {
        try {
            $this->db->exec('START TRANSACTION');

            // Récupérer l'échange mère
            $echange = $this->findById($echangeId);
            if ($echange === null) {
                $this->db->exec('ROLLBACK');
                return false;
            }

            $idProprietaire = (int) $echange['id_proprietaire'];
            $idDemandeur = (int) $echange['id_demandeur'];

            // Récupérer les objets filles de l'échange
            $echangeFilles = $this->getEchangeFilles($echangeId);

            foreach ($echangeFilles as $fille) {
                $objetId = (int) $fille['id_objet'];
                $qttEchange = (int) $fille['qtt'];
                $oldProprietaire = (int) $fille['id_proprietaire'];

                // Déterminer le nouveau propriétaire
                // Si l'ancien proprio est le propriétaire de l'échange, le nouveau est le demandeur
                // Et vice versa
                $newProprietaire = ($oldProprietaire === $idProprietaire) ? $idDemandeur : $idProprietaire;

                // Récupérer l'objet actuel
                $objet = $objetModel->findById($objetId);
                if ($objet === null) {
                    error_log("Objet $objetId introuvable lors du traitement de l'échange $echangeId");
                    continue;
                }

                $qttObjet = (int) $objet['qtt'];

                if ($qttEchange >= $qttObjet) {
                    // Cas 1: Quantité échangée >= quantité totale
                    // Transfert direct de propriété
                    $objetModel->changeProprietaire($objetId, $newProprietaire);
                    error_log("Échange $echangeId: Objet $objetId transféré directement à l'utilisateur $newProprietaire");
                } else {
                    // Cas 2: Quantité échangée < quantité totale
                    // Créer un nouvel objet pour le nouveau propriétaire
                    $newObjetId = $objetModel->duplicateForNewOwner($objetId, $newProprietaire, $qttEchange);
                    
                    if ($newObjetId === false) {
                        error_log("Erreur création nouvel objet pour l'échange $echangeId");
                        $this->db->exec('ROLLBACK');
                        return false;
                    }

                    // Soustraire la quantité de l'objet original
                    $newQtt = $qttObjet - $qttEchange;
                    $objetModel->updateQuantite($objetId, $newQtt);
                    
                    error_log("Échange $echangeId: Nouvel objet $newObjetId créé (qtt: $qttEchange) pour utilisateur $newProprietaire. Objet $objetId mis à jour (qtt: $newQtt)");
                }
            }

            // Mettre à jour le statut de l'échange
            $this->updateStatus($echangeId, self::STATUS_ACCEPTE);

            $this->db->exec('COMMIT');
            return true;

        } catch (\Exception $e) {
            $this->db->exec('ROLLBACK');
            error_log('Erreur traitement échange accepté: ' . $e->getMessage());
            return false;
        }
    }
}
