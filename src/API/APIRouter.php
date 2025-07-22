<?php

class APIRouter {
    private $controller;
    private $authMiddleware;
    
    public function __construct(APIController $controller, APIAuthMiddleware $authMiddleware) {
        $this->controller = $controller;
        $this->authMiddleware = $authMiddleware;
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $this->getPath();
        
        // Routes publiques (sans authentification)
        if ($this->matchRoute($method, $path, 'POST', '/auth/login')) {
            return $this->controller->login();
        }
        
        if ($this->matchRoute($method, $path, 'POST', '/auth/refresh')) {
            return $this->controller->refreshToken();
        }
        
        if ($this->matchRoute($method, $path, 'GET', '/docs')) {
            return $this->controller->getDocumentation();
        }
        
        // Middleware d'authentification pour les autres routes
        $this->authMiddleware->handle();
        
        // Routes des étudiants
        if ($this->matchRoute($method, $path, 'GET', '/students')) {
            return $this->controller->getStudents();
        }
        
        if (preg_match('/^\/students\/([A-Z0-9]+)$/', $path, $matches)) {
            $matricule = $matches[1];
            
            switch ($method) {
                case 'GET':
                    return $this->controller->getStudent($matricule);
                case 'PUT':
                    return $this->controller->updateStudent($matricule);
                case 'DELETE':
                    return $this->controller->deleteStudent($matricule);
            }
        }
        
        if ($this->matchRoute($method, $path, 'POST', '/students')) {
            return $this->controller->createStudent();
        }
        
        // Routes des notes
        if (preg_match('/^\/students\/([A-Z0-9]+)\/grades$/', $path, $matches)) {
            $matricule = $matches[1];
            if ($method === 'GET') {
                return $this->controller->getStudentGrades($matricule);
            }
        }
        
        if ($this->matchRoute($method, $path, 'GET', '/grades')) {
            return $this->controller->getAllGrades();
        }
        
        if ($this->matchRoute($method, $path, 'POST', '/grades')) {
            return $this->controller->createGrade();
        }
        
        if (preg_match('/^\/grades\/(\d+)$/', $path, $matches)) {
            $id = (int) $matches[1];
            
            switch ($method) {
                case 'PUT':
                    return $this->controller->updateGrade($id);
                case 'DELETE':
                    return $this->controller->deleteGrade($id);
            }
        }
        
        // Routes des formations
        if ($this->matchRoute($method, $path, 'GET', '/formations')) {
            return $this->controller->getFormations();
        }
        
        if ($this->matchRoute($method, $path, 'POST', '/formations')) {
            return $this->controller->createFormation();
        }
        
        // Routes des statistiques
        if ($this->matchRoute($method, $path, 'GET', '/stats/overview')) {
            return $this->controller->getOverviewStats();
        }
        
        if (preg_match('/^\/stats\/students\/([A-Z0-9]+)$/', $path, $matches)) {
            $matricule = $matches[1];
            if ($method === 'GET') {
                return $this->controller->getStudentStats($matricule);
            }
        }
        
        // Route non trouvée
        throw new APIException(404, 'Endpoint non trouvé');
    }
    
    private function getPath(): string {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Supprimer le préfixe /api/v1 si présent
        $path = preg_replace('/^\/api\/v1/', '', $path);
        
        // Supprimer le slash final
        $path = rtrim($path, '/');
        
        return $path ?: '/';
    }
    
    private function matchRoute(string $method, string $path, string $expectedMethod, string $expectedPath): bool {
        return $method === $expectedMethod && $path === $expectedPath;
    }
}
