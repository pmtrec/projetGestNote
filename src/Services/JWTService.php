<?php

class JWTService {
    private $secretKey;
    private $algorithm;
    private $tokenExpiry;
    private $refreshTokenExpiry;
    
    public function __construct() {
        $this->secretKey = $_ENV['JWT_SECRET'] ?? 'your-secret-key-change-in-production';
        $this->algorithm = 'HS256';
        $this->tokenExpiry = 3600; // 1 heure
        $this->refreshTokenExpiry = 604800; // 7 jours
    }
    
    public function generateToken(array $user): string {
        $header = json_encode(['typ' => 'JWT', 'alg' => $this->algorithm]);
        $payload = json_encode([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'matricule' => $user['matricule'],
            'role' => $user['role'],
            'iat' => time(),
            'exp' => time() + $this->tokenExpiry
        ]);
        
        $base64Header = $this->base64UrlEncode($header);
        $base64Payload = $this->base64UrlEncode($payload);
        
        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $this->secretKey, true);
        $base64Signature = $this->base64UrlEncode($signature);
        
        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }
    
    public function generateRefreshToken(array $user): string {
        $header = json_encode(['typ' => 'JWT', 'alg' => $this->algorithm]);
        $payload = json_encode([
            'user_id' => $user['id'],
            'type' => 'refresh',
            'iat' => time(),
            'exp' => time() + $this->refreshTokenExpiry
        ]);
        
        $base64Header = $this->base64UrlEncode($header);
        $base64Payload = $this->base64UrlEncode($payload);
        
        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $this->secretKey, true);
        $base64Signature = $this->base64UrlEncode($signature);
        
        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }
    
    public function validateToken(string $token): array {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            throw new Exception('Token invalide');
        }
        
        [$header, $payload, $signature] = $parts;
        
        // Vérifier la signature
        $expectedSignature = hash_hmac('sha256', $header . "." . $payload, $this->secretKey, true);
        $expectedSignature = $this->base64UrlEncode($expectedSignature);
        
        if (!hash_equals($expectedSignature, $signature)) {
            throw new Exception('Signature invalide');
        }
        
        // Décoder le payload
        $payloadData = json_decode($this->base64UrlDecode($payload), true);
        
        if (!$payloadData) {
            throw new Exception('Payload invalide');
        }
        
        // Vérifier l'expiration
        if (isset($payloadData['exp']) && $payloadData['exp'] < time()) {
            throw new Exception('Token expiré');
        }
        
        return $payloadData;
    }
    
    public function validateRefreshToken(string $token): array {
        $payload = $this->validateToken($token);
        
        if (!isset($payload['type']) || $payload['type'] !== 'refresh') {
            throw new Exception('Token de rafraîchissement invalide');
        }
        
        return $payload;
    }
    
    private function base64UrlEncode(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    private function base64UrlDecode(string $data): string {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
