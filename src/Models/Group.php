<?php

namespace App\Models;
require __DIR__ . '/../../vendor/autoload.php';
use PDO;

class Group {
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

    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO groups (name) VALUES (:name)");
        return $stmt->execute(['name' => $name]);
    }
}
