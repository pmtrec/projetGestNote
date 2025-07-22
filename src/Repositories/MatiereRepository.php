<?php

interface MatiereRepositoryInterface {
    public function findAll(): array;
    public function findByCode(string $code): ?array;
    public function create(array $matiereData): bool;
}

class MatiereRepository implements MatiereRepositoryInterface {
    private $db;
    
    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }
    
    public function findAll(): array {
        $sql = "SELECT m.*, f.libelle as formation_libelle 
                FROM matieres m 
                LEFT JOIN formations f ON m.formation_id = f.id 
                ORDER BY f.libelle, m.libelle";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function findByCode(string $code): ?array {
        $sql = "SELECT m.*, f.libelle as formation_libelle 
                FROM matieres m 
                LEFT JOIN formations f ON m.formation_id = f.id 
                WHERE m.code = :code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['code' => $code]);
        return $stmt->fetch() ?: null;
    }
    
    public function create(array $matiereData): bool {
        $sql = "INSERT INTO matieres (code, libelle, formation_id, coefficient) 
                VALUES (:code, :libelle, :formation_id, :coefficient)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($matiereData);
    }
}
