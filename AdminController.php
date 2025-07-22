<?php

/**
 * Contrôleur administrateur
 * Respecte le principe Single Responsibility
 */
class AdminController {
    private $authService;
    private $studentRepository;
    private $gradeRepository;
    private $gradeCalculator;
    
    public function __construct(
        AuthService $authService,
        StudentRepositoryInterface $studentRepository,
        GradeRepositoryInterface $gradeRepository,
        GradeCalculatorService $gradeCalculator,
        UserRepositoryInterface $userRepository // Ajout du UserRepository
    ) {
        $this->authService = $authService;
        $this->studentRepository = $studentRepository;
        $this->gradeRepository = $gradeRepository;
        $this->gradeCalculator = $gradeCalculator;
        $this->userRepository = $userRepository; // Initialisation du UserRepository
    }
    
    public function dashboard(): void {
        $this->checkAdminAccess();
        
        $students = $this->studentRepository->findAll();
        $grades = $this->gradeRepository->findAll();
        
        $stats = [
            'total_students' => count($students),
            'total_grades' => count($grades),
            'average_general' => $this->calculateGeneralAverage($grades)
        ];
        
        include 'views/admin/dashboard.php';
    }
    
    public function students(): void {
        $this->checkAdminAccess();
        
        $students = $this->studentRepository->findAll();
        include 'views/admin/students.php';
    }
    
    public function grades(): void {
        $this->checkAdminAccess();
        
        $grades = $this->gradeRepository->findAll();
        include 'views/admin/grades.php';
    }

    public function createStudent(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'matricule' => $_POST['matricule'] ?? '',
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'formation_id' => $_POST['formation_id'] ?? null,
                'user_id' => null
            ];
            
            if (empty($data['matricule']) || empty($data['nom']) || empty($data['prenom'])) {
                echo json_encode(['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis']);
                return;
            }
            
            // Créer d'abord l'utilisateur
            $userData = [
                'matricule' => $data['matricule'],
                'password' => 'password', // Mot de passe par défaut
                'role' => 'student'
            ];
            
            if ($this->userRepository->create($userData)) {
                // Récupérer l'ID de l'utilisateur créé
                $user = $this->userRepository->findByCredentials($data['matricule'], 'password');
                $data['user_id'] = $user['id'];
                
                if ($this->studentRepository->create($data)) {
                    echo json_encode(['success' => true, 'message' => 'Étudiant inscrit avec succès']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'inscription']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du compte']);
            }
        }
    }

    public function createAdmin(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Email et mot de passe requis']);
                return;
            }
            
            if ($password !== $confirmPassword) {
                echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas']);
                return;
            }
            
            $userData = [
                'email' => $email,
                'password' => $password,
                'role' => 'admin'
            ];
            
            if ($this->userRepository->create($userData)) {
                echo json_encode(['success' => true, 'message' => 'Administrateur créé avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
            }
        }
    }
    
    private function checkAdminAccess(): void {
        $user = $this->authService->getCurrentUser();
        if (!$user || !$user->isAdmin()) {
            header('Location: /');
            exit;
        }
    }
    
    private function calculateGeneralAverage(array $grades): float {
        if (empty($grades)) return 0;
        
        $total = 0;
        foreach ($grades as $grade) {
            $total += $grade['note'];
        }
        
        return $total / count($grades);
    }
}
