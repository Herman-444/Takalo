-- Script d'initialisation des statuts d'échange
-- À exécuter une fois pour créer les statuts nécessaires

USE takalo;

-- Insertion des statuts
INSERT INTO status (id, name) VALUES 
(1, 'En attente'),
(2, 'Accepté'),
(3, 'Refusé')
ON DUPLICATE KEY UPDATE name = VALUES(name);
