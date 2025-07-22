<?php
require_once '../src/Config/Database.php';
require_once '../src/API/APIRouter.php';
require_once '../src/API/APIController.php';
require_once '../src/API/APIException.php';
require_once '../src/Services/JWTService.php';
require_once '../src/Middleware/APIAuthMiddleware.php';
require_once '../src/Middleware/RateLimiter.php';
require_once '../src/Repositories/UserRepository.php';
require_once '../src/Repositories/StudentRepository.php';
require_once '../src/Repositories/GradeRepository.php';
require_once '../src/Repositories/FormationRepository.php';
require_once '../src/Repositories/MatiereRepository.php';

// Configuration CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Gestion des erreurs globales
set_exception_handler(function($exception) {
    http_response_code($exception instanceof APIException ? $exception->getCode() : 500);
    echo json_encode([
        'success' => false,
        'error' => [
            'code' => $exception instanceof APIException ? $exception->getCode() : 500,
            'message' => $exception->getMessage(),
            'details' => $exception instanceof APIException ? $exception->getDetails() : null
        ],
        'timestamp' => date('c')
    ]);
});

try {
    // Initialisation de la base de données
    $database = new Database();
    
    // Initialisation des repositories
    $userRepository = new UserRepository($database);
    $studentRepository = new StudentRepository($database);
    $gradeRepository = new GradeRepository($database);
    $formationRepository = new FormationRepository($database);
    $matiereRepository = new MatiereRepository($database);
    
    // Initialisation des services
    $jwtService = new JWTService();
    
    // Middleware de rate limiting
    $rateLimiter = new RateLimiter();
    $rateLimiter->check();
    
    // Initialisation du contrôleur API
    $apiController = new APIController(
        $userRepository,
        $studentRepository,
        $gradeRepository,
        $formationRepository,
        $matiereRepository,
        $jwtService
    );
    
    // Initialisation du routeur
    $router = new APIRouter($apiController, new APIAuthMiddleware($jwtService, $userRepository));
    
    // Traitement de la requête
    $router->handleRequest();
    
} catch (Exception $e) {
    throw new APIException(500, 'Erreur interne du serveur', $e->getMessage());
}
