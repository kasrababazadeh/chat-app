<?php

namespace App\Services;
require __DIR__ . '/../../vendor/autoload.php';
use PDO;

class MessageService {
    protected $pdo;

    public function __construct() {
        $this->pdo = \Database::connect();
    }

    public function sendMessage($groupId, $userId, $message) {
        $stmt = $this->pdo->prepare("INSERT INTO messages (group_id, user_id, message) VALUES (:group_id, :user_id, :message)");

        try {
            $stmt->execute([':group_id' => $groupId, ':user_id' => $userId, ':message' => $message]);
            return $this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            return false;
        }
    }
}
