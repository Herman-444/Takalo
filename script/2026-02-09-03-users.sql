-- Script d'insertion des utilisateurs
-- 2 utilisateurs admin et 1 utilisateur non-admin

USE takalo;

-- Insertion des utilisateurs
-- Mot de passe stocké en clair (non recommandé en production)

INSERT INTO users (username, password, type) VALUES 
('admin', 'admin123', 'admin'),
('superadmin', 'admin123', 'admin'),
('user', 'admin123', 'user');
