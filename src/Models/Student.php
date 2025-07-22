<?php

class Student {
    private $matricule;
    private $nom;
    private $prenom;
    private $adresse;
    private $telephone;
    private $formation;
    private $formationId;
    
    public function __construct(array $data) {
        $this->matricule = $data['matricule'];
        $this->nom = $data['nom'];
        $this->prenom = $data['prenom'];
        $this->adresse = $data['adresse'] ?? '';
        $this->telephone = $data['telephone'] ?? '';
        $this->formation = $data['formation_libelle'] ?? null;
        $this->formationId = $data['formation_id'] ?? null;
    }
    
    public function getMatricule(): string { return $this->matricule; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getAdresse(): string { return $this->adresse; }
    public function getTelephone(): string { return $this->telephone; }
    public function getFormation(): ?string { return $this->formation; }
    public function getFormationId(): ?int { return $this->formationId; }
    
    public function getFullName(): string {
        return $this->prenom . ' ' . $this->nom;
    }
}
