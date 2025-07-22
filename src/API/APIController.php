<?php

class APIController {
    private $userRepository;
    private $studentRepository;
    private $gradeRepository;
    private $formationRepository;
    private $matiereRepository;
    private $jwtService;
    
    public function __construct(
        UserRepositoryInterface $userRepository,
        StudentRepositoryInterface $studentRepository,
        GradeRepositoryInterface $gradeRepository,
        FormationRepositoryInterface $formationRepository,
        MatiereRepositoryInterface $matiereRepository,
        JWTService $jwtService
    ) {
        $this->userRepository = $userRepository;
        $this->studentRepository = $studentRepository;
        $this->gradeRepository = $gradeRepository;
        $this->formationRepository = $formationRepository;
        $this->matiereRepository = $matiereRepository;
        $this->jwtService = $jwtService;
    }
    
    // Authentification
    public function login() {
        $data = $this->getJsonInput();
        
        if (!isset($data['login']) || !isset($data['password'])) {
            throw new APIException(400, 'Login et mot de passe requis');
        }
        
        $user = $this->userRepository->findByCredentials($data['login'], $data['password']);
        
        if (!$user) {
            throw new APIException(401, 'Identifiants invalides');
        }
        
        $token = $this->jwtService->generateToken($user);
        $refreshToken = $this->jwtService->generateRefreshToken($user);
        
        $this->sendSuccess([
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'matricule' => $user['matricule'],
                'role' => $user['role']
            ],
            'token' => $token,
            'refresh_token' => $refreshToken,
            'expires_in' => 3600
        ]);
    }
    
    public function refreshToken() {
        $data = $this->getJsonInput();
        
        if (!isset($data['refresh_token'])) {
            throw new APIException(400, 'Refresh token requis');
        }
        
        try {
            $payload = $this->jwtService->validateRefreshToken($data['refresh_token']);
            $user = $this->userRepository->findById($payload['user_id']);
            
            if (!$user) {
                throw new APIException(401, 'Utilisateur non trouvé');
            }
            
            $token = $this->jwtService->generateToken($user);
            
            $this->sendSuccess([
                'token' => $token,
                'expires_in' => 3600
            ]);
            
        } catch (Exception $e) {
            throw new APIException(401, 'Refresh token invalide');
        }
    }
    
    // Étudiants
    public function getStudents() {
        $page = (int) ($_GET['page'] ?? 1);
        $limit = min((int) ($_GET['limit'] ?? 20), 100);
        $search = $_GET['search'] ?? '';
        
        $students = $this->studentRepository->findAllPaginated($page, $limit, $search);
        $total = $this->studentRepository->count($search);
        
        $this->sendSuccess([
            'students' => array_map([$this, 'formatStudent'], $students),
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }
    
    public function getStudent(string $matricule) {
        $student = $this->studentRepository->findByMatricule($matricule);
        
        if (!$student) {
            throw new APIException(404, 'Étudiant non trouvé');
        }
        
        $grades = $this->gradeRepository->findByStudent($matricule);
        
        $this->sendSuccess([
            'student' => $this->formatStudent($student),
            'grades' => array_map([$this, 'formatGrade'], $grades),
            'statistics' => $this->calculateStudentStats($grades)
        ]);
    }
    
    public function createStudent() {
        $this->requireAdmin();
        $data = $this->getJsonInput();
        
        $this->validateStudentData($data);
        
        // Créer l'utilisateur d'abord
        $userData = [
            'matricule' => $data['matricule'],
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? 'password123',
            'role' => 'student'
        ];
        
        if (!$this->userRepository->create($userData)) {
            throw new APIException(500, 'Erreur lors de la création de l\'utilisateur');
        }
        
        $userId = $this->userRepository->getLastInsertId();
        
        // Créer l'étudiant
        $studentData = [
            'matricule' => $data['matricule'],
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'adresse' => $data['adresse'] ?? '',
            'telephone' => $data['telephone'] ?? '',
            'formation_id' => $data['formation_id'] ?? null,
            'user_id' => $userId
        ];
        
        if (!$this->studentRepository->create($studentData)) {
            throw new APIException(500, 'Erreur lors de la création de l\'étudiant');
        }
        
        $student = $this->studentRepository->findByMatricule($data['matricule']);
        
        $this->sendSuccess([
            'message' => 'Étudiant créé avec succès',
            'student' => $this->formatStudent($student)
        ], 201);
    }
    
    public function updateStudent(string $matricule) {
        $this->requireAdmin();
        $data = $this->getJsonInput();
        
        $student = $this->studentRepository->findByMatricule($matricule);
        if (!$student) {
            throw new APIException(404, 'Étudiant non trouvé');
        }
        
        $updateData = [
            'nom' => $data['nom'] ?? $student['nom'],
            'prenom' => $data['prenom'] ?? $student['prenom'],
            'adresse' => $data['adresse'] ?? $student['adresse'],
            'telephone' => $data['telephone'] ?? $student['telephone'],
            'formation_id' => $data['formation_id'] ?? $student['formation_id']
        ];
        
        if (!$this->studentRepository->update($matricule, $updateData)) {
            throw new APIException(500, 'Erreur lors de la mise à jour');
        }
        
        $updatedStudent = $this->studentRepository->findByMatricule($matricule);
        
        $this->sendSuccess([
            'message' => 'Étudiant mis à jour avec succès',
            'student' => $this->formatStudent($updatedStudent)
        ]);
    }
    
    public function deleteStudent(string $matricule) {
        $this->requireAdmin();
        
        $student = $this->studentRepository->findByMatricule($matricule);
        if (!$student) {
            throw new APIException(404, 'Étudiant non trouvé');
        }
        
        if (!$this->studentRepository->delete($matricule)) {
            throw new APIException(500, 'Erreur lors de la suppression');
        }
        
        $this->sendSuccess(['message' => 'Étudiant supprimé avec succès']);
    }
    
    // Notes
    public function getStudentGrades(string $matricule) {
        $student = $this->studentRepository->findByMatricule($matricule);
        if (!$student) {
            throw new APIException(404, 'Étudiant non trouvé');
        }
        
        $grades = $this->gradeRepository->findByStudent($matricule);
        
        $this->sendSuccess([
            'matricule' => $matricule,
            'grades' => array_map([$this, 'formatGrade'], $grades),
            'statistics' => $this->calculateStudentStats($grades)
        ]);
    }
    
    public function createGrade() {
        $this->requireAdmin();
        $data = $this->getJsonInput();
        
        $this->validateGradeData($data);
        
        // Vérifier si la note existe déjà
        $existing = $this->gradeRepository->findByStudentAndSubject(
            $data['matricule'], 
            $data['matiere_code']
        );
        
        if ($existing) {
            throw new APIException(409, 'Une note existe déjà pour cette matière');
        }
        
        $gradeData = [
            'matricule' => $data['matricule'],
            'matiere_code' => $data['matiere_code'],
            'note' => $data['note']
        ];
        
        if (!$this->gradeRepository->create($gradeData)) {
            throw new APIException(500, 'Erreur lors de la création de la note');
        }
        
        $grade = $this->gradeRepository->findByStudentAndSubject(
            $data['matricule'], 
            $data['matiere_code']
        );
        
        $this->sendSuccess([
            'message' => 'Note créée avec succès',
            'grade' => $this->formatGrade($grade)
        ], 201);
    }
    
    public function updateGrade(int $id) {
        $this->requireAdmin();
        $data = $this->getJsonInput();
        
        if (!isset($data['note']) || !is_numeric($data['note'])) {
            throw new APIException(400, 'Note invalide');
        }
        
        $note = (float) $data['note'];
        if ($note < 0 || $note > 20) {
            throw new APIException(400, 'La note doit être entre 0 et 20');
        }
        
        if (!$this->gradeRepository->update($id, $note)) {
            throw new APIException(500, 'Erreur lors de la mise à jour');
        }
        
        $this->sendSuccess(['message' => 'Note mise à jour avec succès']);
    }
    
    public function deleteGrade(int $id) {
        $this->requireAdmin();
        
        if (!$this->gradeRepository->delete($id)) {
            throw new APIException(500, 'Erreur lors de la suppression');
        }
        
        $this->sendSuccess(['message' => 'Note supprimée avec succès']);
    }
    
    public function getAllGrades() {
        $page = (int) ($_GET['page'] ?? 1);
        $limit = min((int) ($_GET['limit'] ?? 50), 100);
        $matricule = $_GET['matricule'] ?? '';
        $matiere = $_GET['matiere'] ?? '';
        
        $grades = $this->gradeRepository->findAllPaginated($page, $limit, $matricule, $matiere);
        $total = $this->gradeRepository->count($matricule, $matiere);
        
        $this->sendSuccess([
            'grades' => array_map([$this, 'formatGrade'], $grades),
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }
    
    // Formations
    public function getFormations() {
        $formations = $this->formationRepository->findAll();
        
        $this->sendSuccess([
            'formations' => array_map([$this, 'formatFormation'], $formations)
        ]);
    }
    
    public function createFormation() {
        $this->requireAdmin();
        $data = $this->getJsonInput();
        
        if (!isset($data['libelle']) || empty(trim($data['libelle']))) {
            throw new APIException(400, 'Libellé de formation requis');
        }
        
        $formationData = ['libelle' => trim($data['libelle'])];
        
        if (!$this->formationRepository->create($formationData)) {
            throw new APIException(500, 'Erreur lors de la création de la formation');
        }
        
        $this->sendSuccess([
            'message' => 'Formation créée avec succès'
        ], 201);
    }
    
    // Statistiques
    public function getOverviewStats() {
        $stats = [
            'students_count' => $this->studentRepository->count(),
            'formations_count' => $this->formationRepository->count(),
            'grades_count' => $this->gradeRepository->count(),
            'average_grade' => $this->gradeRepository->getAverageGrade(),
            'success_rate' => $this->gradeRepository->getSuccessRate(),
            'top_students' => $this->getTopStudents(5),
            'grades_distribution' => $this->getGradesDistribution()
        ];
        
        $this->sendSuccess($stats);
    }
    
    public function getStudentStats(string $matricule) {
        $student = $this->studentRepository->findByMatricule($matricule);
        if (!$student) {
            throw new APIException(404, 'Étudiant non trouvé');
        }
        
        $grades = $this->gradeRepository->findByStudent($matricule);
        $stats = $this->calculateStudentStats($grades);
        
        $this->sendSuccess([
            'matricule' => $matricule,
            'student_name' => $student['prenom'] . ' ' . $student['nom'],
            'statistics' => $stats
        ]);
    }
    
    // Documentation
    public function getDocumentation() {
        $docs = [
            'title' => 'API Système de Gestion des Notes',
            'version' => '1.0.0',
            'description' => 'API REST pour la gestion des étudiants, notes et formations',
            'base_url' => 'https://votre-domaine.com/api/v1',
            'authentication' => [
                'type' => 'Bearer Token (JWT)',
                'header' => 'Authorization: Bearer {token}',
                'login_endpoint' => '/auth/login',
                'refresh_endpoint' => '/auth/refresh'
            ],
            'endpoints' => [
                'students' => [
                    'GET /students' => 'Liste des étudiants (paginée)',
                    'GET /students/{matricule}' => 'Détails d\'un étudiant',
                    'POST /students' => 'Créer un étudiant (admin)',
                    'PUT /students/{matricule}' => 'Modifier un étudiant (admin)',
                    'DELETE /students/{matricule}' => 'Supprimer un étudiant (admin)'
                ],
                'grades' => [
                    'GET /students/{matricule}/grades' => 'Notes d\'un étudiant',
                    'GET /grades' => 'Toutes les notes (paginées)',
                    'POST /grades' => 'Créer une note (admin)',
                    'PUT /grades/{id}' => 'Modifier une note (admin)',
                    'DELETE /grades/{id}' => 'Supprimer une note (admin)'
                ],
                'formations' => [
                    'GET /formations' => 'Liste des formations',
                    'POST /formations' => 'Créer une formation (admin)'
                ],
                'statistics' => [
                    'GET /stats/overview' => 'Statistiques générales',
                    'GET /stats/students/{matricule}' => 'Statistiques d\'un étudiant'
                ]
            ],
            'rate_limiting' => '100 requêtes par heure par IP',
            'response_format' => [
                'success' => [
                    'success' => true,
                    'data' => '...',
                    'timestamp' => '2024-01-01T12:00:00Z'
                ],
                'error' => [
                    'success' => false,
                    'error' => [
                        'code' => 400,
                        'message' => 'Message d\'erreur',
                        'details' => 'Détails optionnels'
                    ],
                    'timestamp' => '2024-01-01T12:00:00Z'
                ]
            ]
        ];
        
        $this->sendSuccess($docs);
    }
    
    // Méthodes utilitaires
    private function getJsonInput(): array {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new APIException(400, 'JSON invalide');
        }
        
        return $data ?? [];
    }
    
    private function sendSuccess($data, int $code = 200) {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'data' => $data,
            'timestamp' => date('c')
        ]);
    }
    
    private function requireAdmin() {
        $user = $_SESSION['api_user'] ?? null;
        if (!$user || $user['role'] !== 'admin') {
            throw new APIException(403, 'Accès administrateur requis');
        }
    }
    
    private function formatStudent(array $student): array {
        return [
            'matricule' => $student['matricule'],
            'nom' => $student['nom'],
            'prenom' => $student['prenom'],
            'full_name' => $student['prenom'] . ' ' . $student['nom'],
            'adresse' => $student['adresse'],
            'telephone' => $student['telephone'],
            'formation' => [
                'id' => $student['formation_id'],
                'libelle' => $student['formation_libelle']
            ],
            'created_at' => $student['created_at'],
            'updated_at' => $student['updated_at']
        ];
    }
    
    private function formatGrade(array $grade): array {
        return [
            'id' => $grade['id'],
            'matricule' => $grade['matricule'],
            'matiere' => [
                'code' => $grade['matiere_code'],
                'libelle' => $grade['matiere_libelle'],
                'coefficient' => (int) $grade['coefficient']
            ],
            'note' => (float) $grade['note'],
            'points' => (float) $grade['note'] * (int) $grade['coefficient'],
            'status' => $this->getGradeStatus($grade['note']),
            'validated' => $grade['note'] >= 10,
            'created_at' => $grade['created_at'],
            'updated_at' => $grade['updated_at']
        ];
    }
    
    private function formatFormation(array $formation): array {
        return [
            'id' => $formation['id'],
            'libelle' => $formation['libelle'],
            'nb_matieres' => (int) ($formation['nb_matieres'] ?? 0),
            'created_at' => $formation['created_at']
        ];
    }
    
    private function getGradeStatus(float $note): string {
        if ($note >= 16) return 'excellent';
        if ($note >= 14) return 'bien';
        if ($note >= 12) return 'assez-bien';
        if ($note >= 10) return 'passable';
        return 'insuffisant';
    }
    
    private function calculateStudentStats(array $grades): array {
        if (empty($grades)) {
            return [
                'total_subjects' => 0,
                'average' => 0,
                'total_points' => 0,
                'total_coefficients' => 0,
                'validated_subjects' => 0,
                'success_rate' => 0,
                'mention' => 'aucune'
            ];
        }
        
        $totalPoints = 0;
        $totalCoefficients = 0;
        $validatedSubjects = 0;
        
        foreach ($grades as $grade) {
            $coefficient = (int) $grade['coefficient'];
            $note = (float) $grade['note'];
            
            $totalPoints += $note * $coefficient;
            $totalCoefficients += $coefficient;
            
            if ($note >= 10) {
                $validatedSubjects++;
            }
        }
        
        $average = $totalCoefficients > 0 ? $totalPoints / $totalCoefficients : 0;
        $successRate = count($grades) > 0 ? ($validatedSubjects / count($grades)) * 100 : 0;
        
        return [
            'total_subjects' => count($grades),
            'average' => round($average, 2),
            'total_points' => $totalPoints,
            'total_coefficients' => $totalCoefficients,
            'validated_subjects' => $validatedSubjects,
            'success_rate' => round($successRate, 2),
            'mention' => $this->getMention($average)
        ];
    }
    
    private function getMention(float $average): string {
        if ($average >= 16) return 'très bien';
        if ($average >= 14) return 'bien';
        if ($average >= 12) return 'assez bien';
        if ($average >= 10) return 'passable';
        return 'insuffisant';
    }
    
    private function validateStudentData(array $data) {
        $required = ['matricule', 'nom', 'prenom'];
        
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                throw new APIException(400, "Le champ {$field} est requis");
            }
        }
        
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new APIException(400, 'Email invalide');
        }
    }
    
    private function validateGradeData(array $data) {
        $required = ['matricule', 'matiere_code', 'note'];
        
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new APIException(400, "Le champ {$field} est requis");
            }
        }
        
        $note = (float) $data['note'];
        if ($note < 0 || $note > 20) {
            throw new APIException(400, 'La note doit être entre 0 et 20');
        }
    }
    
    private function getTopStudents(int $limit): array {
        // Cette méthode devrait être implémentée dans le repository
        return [];
    }
    
    private function getGradesDistribution(): array {
        // Cette méthode devrait être implémentée dans le repository
        return [];
    }
}
