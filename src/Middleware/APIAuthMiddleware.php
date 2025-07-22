<?php

class APIAuthMiddleware {
    private $jwtService;
    private $userRepository;
    
    public function __construct(JWTService $jwtService, UserRepositoryInterface $userRepository) {
        $this->jwtService = $jwtService;
        $this->userRepository = $userRepository;
    }
    
    public function handle() {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            throw new APIException(401, 'Token d\'authentification requis');
        }
        
        $token = $matches[1];
        
        try {
            $payload = $this->jwtService->validateToken($token);
            
            // Vérifier que l'utilisateur existe toujours
            $user = $this->userRepository->findById($payload['user_id']);
            
            if (!$user) {
                throw new APIException(401, 'Utilisateur non trouvé');
            }
            
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['api_user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'matricule' => $user['matricule'],
                'role' => $user['role']
            ];
            
        } catch (Exception $e) {
            throw new APIException(401, 'Token invalide: ' . $e->getMessage());
        }
    }
}
