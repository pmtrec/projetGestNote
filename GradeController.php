<?php

class GradeController {
    private $authService;
    private $gradeRepository;
    private $studentRepository;
    private $matiereRepository;
    private $pdfService;
    
    public function __construct(
        AuthService $authService,
        GradeRepositoryInterface $gradeRepository,
        StudentRepositoryInterface $studentRepository,
        MatiereRepositoryInterface $matiereRepository,
        PDFService $pdfService
    ) {
        $this->authService = $authService;
        $this->gradeRepository = $gradeRepository;
        $this->studentRepository = $studentRepository;
        $this->matiereRepository = $matiereRepository;
        $this->pdfService = $pdfService;
    }
    
    public function addGrade(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'matricule' => $_POST['matricule'] ?? '',
                'matiere_code' => $_POST['matiere_code'] ?? '',
                'note' => floatval($_POST['note'] ?? 0)
            ];
            
            if (empty($data['matricule']) || empty($data['matiere_code']) || $data['note'] < 0 || $data['note'] > 20) {
                echo json_encode(['success' => false, 'message' => 'Données invalides']);
                return;
            }
            
            // Vérifier si la note existe déjà
            $existingGrade = $this->gradeRepository->findByStudentAndSubject($data['matricule'], $data['matiere_code']);
            
            if ($existingGrade) {
                // Mettre à jour la note existante
                if ($this->gradeRepository->update($existingGrade['id'], $data['note'])) {
                    echo json_encode(['success' => true, 'message' => 'Note mise à jour avec succès']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
                }
            } else {
                // Créer une nouvelle note
                if ($this->gradeRepository->create($data)) {
                    echo json_encode(['success' => true, 'message' => 'Note ajoutée avec succès']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
                }
            }
            return;
        }
        
        $students = $this->studentRepository->findAll();
        $matieres = $this->matiereRepository->findAll();
        include 'views/admin/add_grade.php';
    }
    
    public function updateGrade(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $note = floatval($_POST['note'] ?? 0);
            
            if ($id <= 0 || $note < 0 || $note > 20) {
                echo json_encode(['success' => false, 'message' => 'Données invalides']);
                return;
            }
            
            if ($this->gradeRepository->update($id, $note)) {
                echo json_encode(['success' => true, 'message' => 'Note modifiée avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification']);
            }
        }
    }
    
    public function downloadTranscript(): void {
        $user = $this->authService->getCurrentUser();
        if (!$user || !$user->isStudent()) {
            header('Location: /');
            exit;
        }
        
        $student = $this->studentRepository->findByMatricule($user->getMatricule());
        $grades = $this->gradeRepository->findByStudent($user->getMatricule());
        
        $gradeCalculator = new GradeCalculatorService();
        $average = $gradeCalculator->calculateAverage($grades);
        
        $filename = $this->pdfService->generateTranscript($student, $grades, $average);
        
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile(__DIR__ . '/../public/downloads/' . $filename);
        exit;
    }
    
    private function checkAdminAccess(): void {
        $user = $this->authService->getCurrentUser();
        if (!$user || !$user->isAdmin()) {
            header('Location: /');
            exit;
        }
    }
}
