<?php

interface StudentRepositoryInterface {
    public function findByMatricule(string $matricule): ?array;
    public function findAll(): array;
    public function create(array $studentData): bool;
    public function update(string $matricule, array $studentData): bool;
}

class StudentRepository implements StudentRepositoryInterface {
    private $db;
    
    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }
    
    public function findByMatricule(string $matricule): ?array {
        $sql = "SELECT e.*, f.libelle as formation_libelle 
                FROM etudiants e 
                LEFT JOIN formations f ON e.formation_id = f.id 
                WHERE e.matricule = :matricule";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['matricule' => $matricule]);
        
        return $stmt->fetch() ?: null;
    }
    
    public function findAll(): array {
        $sql = "SELECT e.*, f.libelle as formation_libelle 
                FROM etudiants e 
                LEFT JOIN formations f ON e.formation_id = f.id 
                ORDER BY e.nom, e.prenom";
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll();
    }
    
    public function create(array $studentData): bool {
        $sql = "INSERT INTO etudiants (matricule, nom, prenom, adresse, telephone, formation_id, user_id) 
                VALUES (:matricule, :nom, :prenom, :adresse, :telephone, :formation_id, :user_id)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($studentData);
    }
    
    public function update(string $matricule, array $studentData): bool {
        $sql = "UPDATE etudiants 
                SET nom = :nom, prenom = :prenom, adresse = :adresse, 
                    telephone = :telephone, formation_id = :formation_id 
                WHERE matricule = :matricule";
        $stmt = $this->db->prepare($sql);
        
        $studentData['matricule'] = $matricule;
        return $stmt->execute($studentData);
    }
}
