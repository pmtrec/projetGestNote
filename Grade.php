<?php

/**
 * Interface GradeRepositoryInterface
 */
interface GradeRepositoryInterface {
    public function findByStudent(string $matricule): array;
    public function findByStudentAndSubject(string $matricule, string $matiereCode): ?array;
    public function create(array $gradeData): bool;
    public function update(int $id, float $note): bool;
    public function findAll(): array;
}

/**
 * Classe Grade - Entity
 */
class Grade {
    private $id;
    private $matricule;
    private $matiereCode;
    private $note;
    private $coefficient;
    
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->matricule = $data['matricule'];
        $this->matiereCode = $data['matiere_code'];
        $this->note = (float) $data['note'];
        $this->coefficient = (int) ($data['coefficient'] ?? 1);
    }
    
    // Getters
    public function getId(): ?int { return $this->id; }
    public function getMatricule(): string { return $this->matricule; }
    public function getMatiereCode(): string { return $this->matiereCode; }
    public function getNote(): float { return $this->note; }
    public function getCoefficient(): int { return $this->coefficient; }
    
    public function getPoints(): float {
        return $this->note * $this->coefficient;
    }
    
    public function isValidated(): bool {
        return $this->note >= 10;
    }
}

/**
 * Classe GradeRepository
 */
class GradeRepository implements GradeRepositoryInterface {
    private $db;
    
    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }
    
    public function findByStudent(string $matricule): array {
        $sql = "SELECT n.*, m.libelle as matiere_libelle, m.coefficient 
                FROM notes n 
                JOIN matieres m ON n.matiere_code = m.code 
                WHERE n.matricule = :matricule 
                ORDER BY m.libelle";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['matricule' => $matricule]);
        
        return $stmt->fetchAll();
    }
    
    public function findByStudentAndSubject(string $matricule, string $matiereCode): ?array {
        $sql = "SELECT n.*, m.libelle as matiere_libelle, m.coefficient 
                FROM notes n 
                JOIN matieres m ON n.matiere_code = m.code 
                WHERE n.matricule = :matricule AND n.matiere_code = :matiere_code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['matricule' => $matricule, 'matiere_code' => $matiereCode]);
        
        return $stmt->fetch() ?: null;
    }
    
    public function create(array $gradeData): bool {
        $sql = "INSERT INTO notes (matricule, matiere_code, note) VALUES (:matricule, :matiere_code, :note)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($gradeData);
    }
    
    public function update(int $id, float $note): bool {
        $sql = "UPDATE notes SET note = :note, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute(['id' => $id, 'note' => $note]);
    }
    
    public function findAll(): array {
        $sql = "SELECT n.*, m.libelle as matiere_libelle, m.coefficient, 
                       e.nom, e.prenom 
                FROM notes n 
                JOIN matieres m ON n.matiere_code = m.code 
                JOIN etudiants e ON n.matricule = e.matricule 
                ORDER BY e.nom, e.prenom, m.libelle";
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll();
    }
}
