<?php

/**
 * Service d'authentification
 * Respecte le principe Single Responsibility
 */
class AuthService {
    private $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }
    
    public function login(string $login, string $password): ?User {
        $userData = $this->userRepository->findByCredentials($login, $password);
        
        if ($userData) {
            $user = new User($userData);
            $this->startSession($user);
            return $user;
        }
        
        return null;
    }
    
    public function logout(): void {
        session_destroy();
    }
    
    public function getCurrentUser(): ?User {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        $userData = $this->userRepository->findById($_SESSION['user_id']);
        return $userData ? new User($userData) : null;
    }
    
    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }
    
    private function startSession(User $user): void {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_role'] = $user->getRole();
        $_SESSION['user_login'] = $user->getEmail() ?? $user->getMatricule();
    }
}
