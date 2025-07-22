<?php

class Database {
    private static $instance = null;
    private $connection;
    private $config;
    
    public function __construct(array $config) {
        $this->config = $config;
        $this->connect();
    }
    
    private function connect() {
        try {
            $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->config['username'], $this->config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
    
    public function getConnection(): PDO {
        return $this->connection;
    }
    
    public static function getInstance(array $config = null): Database {
        if (self::$instance === null) {
            self::$instance = new Database($config);
        }
        return self::$instance;
    }
}
