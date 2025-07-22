<?php

interface MatiereRepositoryInterface {
    public function findAll(): array;
    public function findByCode(string $code): ?array;
    public function create(array $matiereData): bool;
    public function update(string $code, array $matiereData): bool;
    public function delete(string $code): bool;
}

class Matiere {
    private $code;
    private $libelle;
    private $coefficient;
    private $formationId;
    
    public function __construct(array $data) {
        $this->code = $data['code'];
        $this->libelle = $data['libelle'];
        $this->coefficient = $data['coefficient'] ?? 1;
        $this->formationId = $data['formation_id'] ?? null;
    }
    
    public function getCode(): string { return $this->code; }
    public function getLibelle(): string { return $this->libelle; }
    public function getCoefficient(): int { return $this->coefficient; }
    public function getFormationId(): ?int { return $this->formationId; }
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
    
    public function update(string $code, array $matiereData): bool {
        $sql = "UPDATE matieres 
                SET libelle = :libelle, formation_id = :formation_id, coefficient = :coefficient 
                WHERE code = :code";
        $stmt = $this->db->prepare($sql);
        $matiereData['code'] = $code;
        return $stmt->execute($matiereData);
    }
    
    public function delete(string $code): bool {
        $sql = "DELETE FROM matieres WHERE code = :code";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['code' => $code]);
    }
}
