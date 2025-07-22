-- Base de données pour le système de gestion des notes
CREATE DATABASE IF NOT EXISTS student_grades_system;
USE student_grades_system;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    matricule VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des formations
CREATE TABLE formations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des matières
CREATE TABLE matieres (
    code VARCHAR(10) PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    formation_id INT,
    coefficient INT DEFAULT 1,
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE CASCADE
);

-- Table des étudiants
CREATE TABLE etudiants (
    matricule VARCHAR(50) PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    adresse TEXT,
    telephone VARCHAR(20),
    formation_id INT,
    user_id INT UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des notes
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(50),
    matiere_code VARCHAR(10),
    note DECIMAL(4,2) CHECK (note >= 0 AND note <= 20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (matricule) REFERENCES etudiants(matricule) ON DELETE CASCADE,
    FOREIGN KEY (matiere_code) REFERENCES matieres(code) ON DELETE CASCADE,
    UNIQUE(matricule, matiere_code)
);

-- Insertion des données de test
INSERT INTO users (email, password, role) VALUES 
('admin@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO formations (libelle) VALUES 
('Informatique'),
('Mathématiques'),
('Physique');

INSERT INTO matieres (code, libelle, formation_id, coefficient) VALUES 
('INFO101', 'Programmation Web', 1, 3),
('INFO102', 'Base de Données', 1, 2),
('INFO103', 'Algorithmes', 1, 3),
('MATH101', 'Analyse', 2, 4),
('MATH102', 'Algèbre', 2, 3),
('PHYS101', 'Mécanique', 3, 3),
('PHYS102', 'Électricité', 3, 2);

INSERT INTO users (matricule, password, role) VALUES 
('ETU001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('ETU002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('ETU003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

INSERT INTO etudiants (matricule, nom, prenom, adresse, telephone, formation_id, user_id) VALUES 
('ETU001', 'Dupont', 'Jean', '123 Rue de la Paix', '0123456789', 1, 2),
('ETU002', 'Martin', 'Marie', '456 Avenue des Fleurs', '0987654321', 1, 3),
('ETU003', 'Bernard', 'Paul', '789 Boulevard Central', '0147258369', 2, 4);

INSERT INTO notes (matricule, matiere_code, note) VALUES 
('ETU001', 'INFO101', 15.5),
('ETU001', 'INFO102', 12.0),
('ETU001', 'INFO103', 18.0),
('ETU002', 'INFO101', 14.0),
('ETU002', 'INFO102', 16.5),
('ETU003', 'MATH101', 13.5),
('ETU003', 'MATH102', 17.0);
