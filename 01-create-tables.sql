-- Création des tables pour le système de gestion des notes

-- Table des utilisateurs (administrateurs et étudiants)
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    matricule VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des formations
CREATE TABLE IF NOT EXISTS formations (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des matières
CREATE TABLE IF NOT EXISTS matieres (
    code VARCHAR(10) PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    formation_id INT,
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE CASCADE
);

-- Table des étudiants
CREATE TABLE IF NOT EXISTS etudiants (
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
CREATE TABLE IF NOT EXISTS notes (
    id SERIAL PRIMARY KEY,
    matricule VARCHAR(50),
    matiere_code VARCHAR(10),
    note DECIMAL(4,2) CHECK (note >= 0 AND note <= 20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (matricule) REFERENCES etudiants(matricule) ON DELETE CASCADE,
    FOREIGN KEY (matiere_code) REFERENCES matieres(code) ON DELETE CASCADE,
    UNIQUE(matricule, matiere_code)
);
