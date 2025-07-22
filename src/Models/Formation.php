<?php

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
