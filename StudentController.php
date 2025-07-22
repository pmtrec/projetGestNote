<?php

/**
 * Contrôleur étudiant
 */
class StudentController {
    private $authService;
    private $studentRepository;
    private $gradeRepository;
    private $gradeCalculator;
    
    public function __construct(
        AuthService $authService,
        StudentRepositoryInterface $studentRepository,
        GradeRepositoryInterface $gradeRepository,
        GradeCalculatorService $gradeCalculator
    ) {
        $this->authService = $authService;
        $this->studentRepository = $studentRepository;
        $this->gradeRepository = $gradeRepository;
        $this->gradeCalculator = $gradeCalculator;
    }
    
    public function dashboard(): void {
        $this->checkStudentAccess();
        
        $user = $this->authService->getCurrentUser();
        $student = $this->studentRepository->findByMatricule($user->getMatricule());
        $grades = $this->gradeRepository->findByStudent($user->getMatricule());
        
        $average = $this->gradeCalculator->calculateAverage($grades);
        $validatedCount = $this->gradeCalculator->getValidatedSubjectsCount($grades);
        
        include 'views/student/dashboard.php';
    }
    
    public function grades(): void {
        $this->checkStudentAccess();
        
        $user = $this->authService->getCurrentUser();
        $grades = $this->gradeRepository->findByStudent($user->getMatricule());
        $average = $this->gradeCalculator->calculateAverage($grades);
        
        include 'views/student/grades.php';
    }

    public function changePassword(): void {
        $this->checkStudentAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($currentPassword) || empty($newPassword)) {
                echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis']);
                return;
            }
            
            if ($newPassword !== $confirmPassword) {
                echo json_encode(['success' => false, 'message' => 'Les nouveaux mots de passe ne correspondent pas']);
                return;
            }
            
            $user = $this->authService->getCurrentUser();
            $userData = $this->userRepository->findById($user->getId());
            
            if (!password_verify($currentPassword, $userData['password'])) {
                echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect']);
                return;
            }
            
            if ($this->userRepository->updatePassword($user->getId(), $newPassword)) {
                echo json_encode(['success' => true, 'message' => 'Mot de passe modifié avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification']);
            }
        }
    }

    public function updateSettings(): void {
        $this->checkStudentAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->authService->getCurrentUser();
            $data = [
                'telephone' => $_POST['telephone'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'notifications' => $_POST['notifications'] ?? '1'
            ];
            
            if ($this->studentRepository->update($user->getMatricule(), $data)) {
                echo json_encode(['success' => true, 'message' => 'Paramètres sauvegardés avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la sauvegarde']);
            }
        }
    }
    
    private function checkStudentAccess(): void {
        $user = $this->authService->getCurrentUser();
        if (!$user || !$user->isStudent()) {
            header('Location: /');
            exit;
        }
    }
}
