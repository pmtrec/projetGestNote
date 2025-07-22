<?php

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
        header('Content-Type: application/json');
        
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($login) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs']);
            return;
        }
        
        $user = $this->authService->login($login, $password);
        
        if ($user) {
            $redirectUrl = $user->isAdmin() ? '/admin/dashboard' : '/student/dashboard';
            echo json_encode(['success' => true, 'redirect' => $redirectUrl]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Identifiants incorrects']);
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
