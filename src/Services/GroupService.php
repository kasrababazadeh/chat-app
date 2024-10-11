<?php

namespace App\Services;
require __DIR__ . '/../../vendor/autoload.php';
use PDO;

class GroupService {
    protected $pdo;

    public function __construct() {
        $this->pdo = \Database::connect();
    }

    public function createGroup($name) {
        $stmt = $this->pdo->prepare("INSERT INTO groups (name) VALUES (:name)");

        try {
            $stmt->execute([':name' => $name]);
            return ['id' => $this->pdo->lastInsertId(), 'name' => $name];
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function getAllGroups() {
        $stmt = $this->pdo->query("SELECT * FROM groups");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function getMessages($groupId) {
        $stmt = $this->pdo->prepare("SELECT m.message, u.username, m.timestamp FROM messages m
                                     JOIN users u ON m.user_id = u.id WHERE m.group_id = :groupId");
        $stmt->execute([':groupId' => $groupId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
