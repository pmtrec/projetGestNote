<?php
session_start();

// Autoloader simple
spl_autoload_register(function ($class) {
    $paths = [
        'src/Models/',
        'src/Controllers/',
        'src/Services/',
        'src/Repositories/',
        'src/Config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Configuration de la base de données
$config = [
    'host' => 'localhost',
    'dbname' => 'student_grades_system',
    'username' => 'root',
    'password' => ''
];

try {
    // Initialisation des dépendances
    $database = new Database($config);
    $userRepository = new UserRepository($database);
    $studentRepository = new StudentRepository($database);
    $gradeRepository = new GradeRepository($database);
    $formationRepository = new FormationRepository($database);
    $matiereRepository = new MatiereRepository($database);
    
    $authService = new AuthService($userRepository);
    $gradeCalculator = new GradeCalculatorService();
    $pdfService = new PDFService();
    
    // Routage simple
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($uri) {
        case '/':
            $controller = new AuthController($authService);
            if ($method === 'POST') {
                $controller->login();
            } else {
                $controller->showLoginForm();
            }
            break;
            
        case '/logout':
            $controller = new AuthController($authService);
            $controller->logout();
            break;
            
        case '/admin/dashboard':
            $controller = new AdminController($authService, $studentRepository, $gradeRepository, $formationRepository, $matiereRepository, $userRepository);
            $controller->dashboard();
            break;
            
        case '/admin/students/create':
            $controller = new AdminController($authService, $studentRepository, $gradeRepository, $formationRepository, $matiereRepository, $userRepository);
            $controller->createStudent();
            break;
            
        case '/admin/formations/create':
            $controller = new AdminController($authService, $studentRepository, $gradeRepository, $formationRepository, $matiereRepository, $userRepository);
            $controller->createFormation();
            break;
            
        case '/admin/grades/create':
            $controller = new AdminController($authService, $studentRepository, $gradeRepository, $formationRepository, $matiereRepository, $userRepository);
            $controller->createGrade();
            break;
            
        case '/admin/grades/update':
            $controller = new AdminController($authService, $studentRepository, $gradeRepository, $formationRepository, $matiereRepository, $userRepository);
            $controller->updateGrade();
            break;
            
        case '/admin/create-admin':
            $controller = new AdminController($authService, $studentRepository, $gradeRepository, $formationRepository, $matiereRepository, $userRepository);
            $controller->createAdmin();
            break;
            
        case '/student/dashboard':
            $controller = new StudentController($authService, $studentRepository, $gradeRepository, $gradeCalculator, $userRepository);
            $controller->dashboard();
            break;
            
        case '/student/change-password':
            $controller = new StudentController($authService, $studentRepository, $gradeRepository, $gradeCalculator, $userRepository);
            $controller->changePassword();
            break;
            
        case '/student/update-settings':
            $controller = new StudentController($authService, $studentRepository, $gradeRepository, $gradeCalculator, $userRepository);
            $controller->updateSettings();
            break;
            
        case '/student/download-transcript':
            $controller = new StudentController($authService, $studentRepository, $gradeRepository, $gradeCalculator, $userRepository);
            $controller->downloadTranscript($pdfService);
            break;
            
        default:
            http_response_code(404);
            echo "Page non trouvée";
            break;
    }
    
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "Erreur système: " . $e->getMessage();
}
?>
