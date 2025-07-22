<?php

interface FormationRepositoryInterface {
    public function findAll(): array;
    public function findById(int $id): ?array;
    public function create(array $formationData): bool;
}

class FormationRepository implements FormationRepositoryInterface {
    private $db;
    
    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }
    
    public function findAll(): array {
        $sql = "SELECT f.*, COUNT(m.code) as nb_matieres 
                FROM formations f 
                LEFT JOIN matieres m ON f.id = m.formation_id 
                GROUP BY f.id 
                ORDER BY f.libelle";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function findById(int $id): ?array {
        $sql = "SELECT * FROM formations WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
    
    public function create(array $formationData): bool {
        $sql = "INSERT INTO formations (libelle) VALUES (:libelle)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['libelle' => $formationData['libelle']]);
    }
}
