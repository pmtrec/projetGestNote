<?php

interface UserRepositoryInterface {
    public function findByCredentials(string $login, string $password): ?array;
    public function findById(int $id): ?array;
    public function create(array $userData): bool;
    public function updatePassword(int $id, string $password): bool;
}

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
    
    public function getLastInsertId(): int {
        return (int) $this->db->lastInsertId();
    }
}
