-- Données de test pour le système

-- Insertion d'un administrateur par défaut
INSERT INTO users (email, password, role) VALUES 
('admin@school.com', '$2b$10$rQZ9QmjlhQZ9QmjlhQZ9Qu', 'admin');

-- Insertion de formations
INSERT INTO formations (libelle) VALUES 
('Informatique'),
('Mathématiques'),
('Physique');

-- Insertion de matières
INSERT INTO matieres (code, libelle, formation_id) VALUES 
('INFO101', 'Programmation Web', 1),
('INFO102', 'Base de Données', 1),
('INFO103', 'Algorithmes', 1),
('MATH101', 'Analyse', 2),
('MATH102', 'Algèbre', 2),
('PHYS101', 'Mécanique', 3),
('PHYS102', 'Électricité', 3);

-- Insertion d'étudiants de test
INSERT INTO users (matricule, password, role) VALUES 
('ETU001', '$2b$10$defaultpassword', 'student'),
('ETU002', '$2b$10$defaultpassword', 'student'),
('ETU003', '$2b$10$defaultpassword', 'student');

INSERT INTO etudiants (matricule, nom, prenom, adresse, telephone, formation_id, user_id) VALUES 
('ETU001', 'Dupont', 'Jean', '123 Rue de la Paix', '0123456789', 1, 2),
('ETU002', 'Martin', 'Marie', '456 Avenue des Fleurs', '0987654321', 1, 3),
('ETU003', 'Bernard', 'Paul', '789 Boulevard Central', '0147258369', 2, 4);

-- Insertion de notes de test
INSERT INTO notes (matricule, matiere_code, note) VALUES 
('ETU001', 'INFO101', 15.5),
('ETU001', 'INFO102', 12.0),
('ETU001', 'INFO103', 18.0),
('ETU002', 'INFO101', 14.0),
('ETU002', 'INFO102', 16.5),
('ETU003', 'MATH101', 13.5),
('ETU003', 'MATH102', 17.0);
