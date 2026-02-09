-- Script d'initialisation des données
-- 2 catégories et 2 objets

USE takalo;

-- Insertion des catégories
INSERT INTO categories (name) VALUES 
('Sport'),
('Nourriture');

-- Insertion de 2 objets appartenant à l'utilisateur 'user' (id = 3)
INSERT INTO objets (title, description, id_proprietaire, id_categorie, prix_estime, qtt) VALUES 
('Ballon de football', 'Ballon de football officiel taille 5', 3, NULL, 25.00, 1),
('Raquette de tennis', 'Raquette de tennis légère pour débutant', 3, NULL, 45.00, 1);
