<?php

class Grade {
    private $id;
    private $matricule;
    private $matiereCode;
    private $matiereLibelle;
    private $note;
    private $coefficient;
    
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->matricule = $data['matricule'];
        $this->matiereCode = $data['matiere_code'];
        $this->matiereLibelle = $data['matiere_libelle'] ?? '';
        $this->note = (float) $data['note'];
        $this->coefficient = (int) ($data['coefficient'] ?? 1);
    }
    
    public function getId(): ?int { return $this->id; }
    public function getMatricule(): string { return $this->matricule; }
    public function getMatiereCode(): string { return $this->matiereCode; }
    public function getMatiereLibelle(): string { return $this->matiereLibelle; }
    public function getNote(): float { return $this->note; }
    public function getCoefficient(): int { return $this->coefficient; }
    
    public function getPoints(): float {
        return $this->note * $this->coefficient;
    }
    
    public function isValidated(): bool {
        return $this->note >= 10;
    }
    
    public function getStatus(): string {
        if ($this->note >= 16) return 'excellent';
        if ($this->note >= 14) return 'bien';
        if ($this->note >= 12) return 'assez-bien';
        if ($this->note >= 10) return 'passable';
        return 'insuffisant';
    }
}
