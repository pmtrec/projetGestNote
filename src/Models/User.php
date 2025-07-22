<?php

class User {
    private $id;
    private $email;
    private $matricule;
    private $role;
    
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->matricule = $data['matricule'] ?? null;
        $this->role = $data['role'] ?? null;
    }
    
    public function getId(): ?int { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function getMatricule(): ?string { return $this->matricule; }
    public function getRole(): ?string { return $this->role; }
    
    public function isAdmin(): bool {
        return $this->role === 'admin';
    }
    
    public function isStudent(): bool {
        return $this->role === 'student';
    }
}
