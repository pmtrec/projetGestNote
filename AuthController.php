<?php

/**
 * Contrôleur d'authentification
 * Respecte le principe Single Responsibility
 */
class AuthController {
    private $authService;
    
    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }
    
    public function showLoginForm(): void {
        if ($this->authService->isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }
        
        include 'views/auth/login.php';
    }
    
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLoginForm();
            return;
        }
        
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($login) || empty($password)) {
            $error = "Veuillez remplir tous les champs";
            include 'views/auth/login.php';
            return;
        }
        
        $user = $this->authService->login($login, $password);
        
        if ($user) {
            $this->redirectToDashboard();
        } else {
            $error = "Identifiants incorrects";
            include 'views/auth/login.php';
        }
    }
    
    public function logout(): void {
        $this->authService->logout();
        header('Location: /');
        exit;
    }
    
    private function redirectToDashboard(): void {
        $user = $this->authService->getCurrentUser();
        if ($user->isAdmin()) {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /student/dashboard');
        }
        exit;
    }
}
