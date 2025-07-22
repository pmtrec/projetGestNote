<?php

class AdminController {
    private $authService;
    private $studentRepository;
    private $gradeRepository;
    private $formationRepository;
    private $matiereRepository;
    private $userRepository;
    
    public function __construct(
        AuthService $authService,
        StudentRepositoryInterface $studentRepository,
        GradeRepositoryInterface $gradeRepository,
        FormationRepositoryInterface $formationRepository,
        MatiereRepositoryInterface $matiereRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->authService = $authService;
        $this->studentRepository = $studentRepository;
        $this->gradeRepository = $gradeRepository;
        $this->formationRepository = $formationRepository;
        $this->matiereRepository = $matiereRepository;
        $this->userRepository = $userRepository;
    }
    
    public function dashboard(): void {
        $this->checkAdminAccess();
        
        $students = $this->studentRepository->findAll();
        $grades = $this->gradeRepository->findAll();
        $formations = $this->formationRepository->findAll();
        $matieres = $this->matiereRepository->findAll();
        
        $stats = [
            'total_students' => count($students),
            'total_grades' => count($grades),
            'total_formations' => count($formations),
            'average_general' => $this->calculateGeneralAverage($grades)
        ];
        
        include 'views/admin/dashboard.php';
    }
    
    public function createStudent(): void {
        $this->checkAdminAccess();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $data = [
            'matricule' => $_POST['matricule'] ?? '',
            'nom' => $_POST['nom'] ?? '',
            'prenom' => $_POST['prenom'] ?? '',
            'adresse' => $_POST['adresse'] ?? '',
            'telephone' => $_POST['telephone'] ?? '',
            'formation_id' => $_POST['formation_id'] ?? null
        ];
        
        if (empty($data['matricule']) || empty($data['nom']) || empty($data['prenom'])) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis']);
            return;
        }
        
        try {
            // Créer d'abord l'utilisateur
            $userData = [
                'matricule' => $data['matricule'],
                'password' => 'password123',
                'role' => 'student'
            ];
            
            if ($this->userRepository->create($userData)) {
                $userId = $this->userRepository->getLastInsertId();
                $data['user_id'] = $userId;
                
                if ($this->studentRepository->create($data)) {
                    echo json_encode(['success' => true, 'message' => 'Étudiant inscrit avec succès']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'inscription']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du compte']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
    }
    
    public function createFormation(): void {
        $this->checkAdminAccess();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $libelle = $_POST['libelle'] ?? '';
        
        if (empty($libelle)) {
            echo json_encode(['success' => false, 'message' => 'Le libellé de la formation est requis']);
            return;
        }
        
        try {
            if ($this->formationRepository->create(['libelle' => $libelle])) {
                echo json_encode(['success' => true, 'message' => 'Formation créée avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la formation']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
    }
    
    public function createGrade(): void {
        $this->checkAdminAccess();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $data = [
            'matricule' => $_POST['matricule'] ?? '',
            'matiere_code' => $_POST['matiere_code'] ?? '',
            'note' => floatval($_POST['note'] ?? 0)
        ];
        
        if (empty($data['matricule']) || empty($data['matiere_code']) || $data['note'] < 0 || $data['note'] > 20) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            return;
        }
        
        try {
            $existingGrade = $this->gradeRepository->findByStudentAndSubject($data['matricule'], $data['matiere_code']);
            
            if ($existingGrade) {
                if ($this->gradeRepository->update($existingGrade['id'], $data['note'])) {
                    echo json_encode(['success' => true, 'message' => 'Note mise à jour avec succès']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
                }
            } else {
                if ($this->gradeRepository->create($data)) {
                    echo json_encode(['success' => true, 'message' => 'Note ajoutée avec succès']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
                }
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
    }
    
    public function updateGrade(): void {
        $this->checkAdminAccess();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $id = intval($_POST['id'] ?? 0);
        $note = floatval($_POST['note'] ?? 0);
        
        if ($id <= 0 || $note < 0 || $note > 20) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            return;
        }
        
        try {
            if ($this->gradeRepository->update($id, $note)) {
                echo json_encode(['success' => true, 'message' => 'Note modifiée avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
    }
    
    public function createAdmin(): void {
        $this->checkAdminAccess();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
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
        
        try {
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
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
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
