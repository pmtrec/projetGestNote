<?php

class StudentController {
    private $authService;
    private $studentRepository;
    private $gradeRepository;
    private $gradeCalculator;
    private $userRepository;
    
    public function __construct(
        AuthService $authService,
        StudentRepositoryInterface $studentRepository,
        GradeRepositoryInterface $gradeRepository,
        GradeCalculatorService $gradeCalculator,
        UserRepositoryInterface $userRepository
    ) {
        $this->authService = $authService;
        $this->studentRepository = $studentRepository;
        $this->gradeRepository = $gradeRepository;
        $this->gradeCalculator = $gradeCalculator;
        $this->userRepository = $userRepository;
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
    
    public function changePassword(): void {
        $this->checkStudentAccess();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
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
        
        try {
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
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
    }
    
    public function updateSettings(): void {
        $this->checkStudentAccess();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        try {
            $user = $this->authService->getCurrentUser();
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'formation_id' => $_POST['formation_id'] ?? null
            ];
            
            if ($this->studentRepository->update($user->getMatricule(), $data)) {
                echo json_encode(['success' => true, 'message' => 'Paramètres sauvegardés avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la sauvegarde']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
    }
    
    public function downloadTranscript(PDFService $pdfService): void {
        $this->checkStudentAccess();
        
        try {
            $user = $this->authService->getCurrentUser();
            $student = $this->studentRepository->findByMatricule($user->getMatricule());
            $grades = $this->gradeRepository->findByStudent($user->getMatricule());
            
            $average = $this->gradeCalculator->calculateAverage($grades);
            
            $filename = $pdfService->generateTranscript($student, $grades, $average);
            
            header('Content-Type: text/html');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile(__DIR__ . '/../../public/downloads/' . $filename);
            exit;
        } catch (Exception $e) {
            echo "Erreur lors de la génération du relevé: " . $e->getMessage();
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
