<?php

class RateLimiter {
    private $maxRequests;
    private $timeWindow;
    private $storageFile;
    
    public function __construct(int $maxRequests = 100, int $timeWindow = 3600) {
        $this->maxRequests = $maxRequests;
        $this->timeWindow = $timeWindow;
        $this->storageFile = sys_get_temp_dir() . '/api_rate_limit.json';
    }
    
    public function check() {
        $clientIp = $this->getClientIp();
        $currentTime = time();
        
        // Charger les données existantes
        $data = $this->loadData();
        
        // Nettoyer les anciennes entrées
        $data = $this->cleanOldEntries($data, $currentTime);
        
        // Vérifier le client actuel
        if (!isset($data[$clientIp])) {
            $data[$clientIp] = [];
        }
        
        // Compter les requêtes dans la fenêtre de temps
        $requestCount = count(array_filter($data[$clientIp], function($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) < $this->timeWindow;
        }));
        
        if ($requestCount >= $this->maxRequests) {
            throw new APIException(429, 'Limite de requêtes dépassée. Essayez plus tard.');
        }
        
        // Ajouter la requête actuelle
        $data[$clientIp][] = $currentTime;
        
        // Sauvegarder les données
        $this->saveData($data);
    }
    
    private function getClientIp(): string {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    private function loadData(): array {
        if (!file_exists($this->storageFile)) {
            return [];
        }
        
        $content = file_get_contents($this->storageFile);
        return json_decode($content, true) ?: [];
    }
    
    private function saveData(array $data) {
        file_put_contents($this->storageFile, json_encode($data), LOCK_EX);
    }
    
    private function cleanOldEntries(array $data, int $currentTime): array {
        foreach ($data as $ip => $timestamps) {
            $data[$ip] = array_filter($timestamps, function($timestamp) use ($currentTime) {
                return ($currentTime - $timestamp) < $this->timeWindow;
            });
            
            if (empty($data[$ip])) {
                unset($data[$ip]);
            }
        }
        
        return $data;
    }
}
