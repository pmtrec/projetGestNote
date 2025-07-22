<?php

/**
 * Interface UserRepositoryInterface
 * Respecte le principe Dependency Inversion
 */
interface UserRepositoryInterface {
    public function findByCredentials(string $login, string $password): ?array;
    public function findById(int $id): ?array;
    public function create(array $userData): bool;
    public function updatePassword(int $id, string $password): bool;
}

/**
 * Classe User - Entity
 * Respecte le principe Single Responsibility
 */
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
    
    // Getters
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

/**
 * Classe UserRepository
 * Respecte les principes Single Responsibility et Open/Closed
 */
class UserRepository implements UserRepositoryInterface {
    private $db;
    
    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }
    
    public function findByCredentials(string $login, string $password): ?array {
        $sql = "SELECT * FROM users WHERE (email = :login OR matricule = :login)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['login' => $login]);
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }
    
    public function findById(int $id): ?array {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch() ?: null;
    }
    
    public function create(array $userData): bool {
        $sql = "INSERT INTO users (email, matricule, password, role) VALUES (:email, :matricule, :password, :role)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'email' => $userData['email'] ?? null,
            'matricule' => $userData['matricule'] ?? null,
            'password' => password_hash($userData['password'], PASSWORD_DEFAULT),
            'role' => $userData['role']
        ]);
    }

    public function updatePassword(int $id, string $password): bool {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $id,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}
