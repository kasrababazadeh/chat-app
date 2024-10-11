<?php

namespace App\Models;

require __DIR__ . '/../../vendor/autoload.php';
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = $this->connect();
    }

    private function connect() {
        $dbPath = $_ENV['DB_PATH'];
        if (!file_exists($dbPath)) {
            die("Database file does not exist: $dbPath");
        }
        return new \PDO("sqlite:$dbPath");
    }
    
    public function register($username) {    
        // Check if username is already taken
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
    
        if ($stmt->fetch()) {
            return false; // Username is taken
        }
    
        // Insert username into the database
        $token = bin2hex(random_bytes(16)); // Generate a random token
        $stmt = $this->db->prepare("INSERT INTO users (username, token) VALUES (:username, :token)");
    
        if ($stmt->execute(['username' => $username, 'token' => $token])) {
            return ['token' => $token, 'id' => $this->db->lastInsertId()];
        }
        
        return false; // Insertion failed
    }
    
}
