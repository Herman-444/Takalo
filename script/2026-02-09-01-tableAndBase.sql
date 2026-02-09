CREATE DATABASE IF NOT EXISTS takalo;
USE takalo;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    type ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS objets(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    id_proprietaire INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_categorie INT,
    prix_estime DECIMAL(10, 2)
);

CREATE TABLE IF NOT EXISTS status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS echangeMere(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_proprietaire INT,
    id_demandeur INT,
    status_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    accepted_at TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS echangeFille(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_echangeMere INT,
    id_objet INT,
    id_proprietaire INT,
    qtt INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

ALTER TABLE objets
ADD COLUMN qtt INT DEFAULT 1;
