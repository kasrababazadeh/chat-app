<?php

namespace App\Models;
require __DIR__ . '/../../vendor/autoload.php';
use PDO;

class Message {
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

    public function send($groupId, $content) {
        $stmt = $this->db->prepare("INSERT INTO messages (group_id, content) VALUES (:group_id, :content)");
        return $stmt->execute(['group_id' => $groupId, 'content' => $content]);
    }

    public function getMessages($groupId) {
        $stmt = $this->db->prepare("SELECT * FROM messages WHERE group_id = :group_id");
        $stmt->execute(['group_id' => $groupId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
