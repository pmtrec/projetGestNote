<?php

interface FormationRepositoryInterface {
    public function findAll(): array;
    public function findById(int $id): ?array;
    public function create(array $formationData): bool;
    public function update(int $id, array $formationData): bool;
    public function delete(int $id): bool;
    public function getMatieresByFormation(int $formationId): array;
}

class Formation {
    private $id;
    private $libelle;
    private $matieres;
    
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->libelle = $data['libelle'];
        $this->matieres = $data['matieres'] ?? [];
    }
    
    public function getId(): ?int { return $this->id; }
    public function getLibelle(): string { return $this->libelle; }
    public function getMatieres(): array { return $this->matieres; }
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
    
    public function update(int $id, array $formationData): bool {
        $sql = "UPDATE formations SET libelle = :libelle WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'libelle' => $formationData['libelle']]);
    }
    
    public function delete(int $id): bool {
        $sql = "DELETE FROM formations WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    public function getMatieresByFormation(int $formationId): array {
        $sql = "SELECT * FROM matieres WHERE formation_id = :formation_id ORDER BY libelle";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['formation_id' => $formationId]);
        return $stmt->fetchAll();
    }
}
