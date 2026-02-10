-- Script d'ajout du statut "Annulée"
-- À exécuter pour ajouter le statut d'annulation

USE takalo;

-- Insertion du statut annulée
INSERT INTO status (id, name) VALUES (4, 'Annulée')
ON DUPLICATE KEY UPDATE name = VALUES(name);
