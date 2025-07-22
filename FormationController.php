<?php

class FormationController {
    private $authService;
    private $formationRepository;
    private $matiereRepository;
    
    public function __construct(
        AuthService $authService,
        FormationRepositoryInterface $formationRepository,
        MatiereRepositoryInterface $matiereRepository
    ) {
        $this->authService = $authService;
        $this->formationRepository = $formationRepository;
        $this->matiereRepository = $matiereRepository;
    }
    
    public function index(): void {
        $this->checkAdminAccess();
        $formations = $this->formationRepository->findAll();
        include 'views/admin/formations.php';
    }
    
    public function create(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = $_POST['libelle'] ?? '';
            
            if (empty($libelle)) {
                echo json_encode(['success' => false, 'message' => 'Le libellé de la formation est requis']);
                return;
            }
            
            if ($this->formationRepository->create(['libelle' => $libelle])) {
                echo json_encode(['success' => true, 'message' => 'Formation créée avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la formation']);
            }
            return;
        }
        
        $formations = $this->formationRepository->findAll();
        include 'views/admin/formations.php';
    }
    
    public function addMatiere(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'code' => $_POST['code'] ?? '',
                'libelle' => $_POST['libelle'] ?? '',
                'formation_id' => $_POST['formation_id'] ?? null,
                'coefficient' => $_POST['coefficient'] ?? 1
            ];
            
            if (empty($data['code']) || empty($data['libelle'])) {
                $error = "Le code et le libellé sont requis";
            } else {
                if ($this->matiereRepository->create($data)) {
                    $success = "Matière ajoutée avec succès";
                } else {
                    $error = "Erreur lors de l'ajout de la matière";
                }
            }
        }
        
        $formations = $this->formationRepository->findAll();
        $matieres = $this->matiereRepository->findAll();
        include 'views/admin/formations.php';
    }
    
    private function checkAdminAccess(): void {
        $user = $this->authService->getCurrentUser();
        if (!$user || !$user->isAdmin()) {
            header('Location: /');
            exit;
        }
    }
}
