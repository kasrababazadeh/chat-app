<?php

namespace App\Services;

use App\Models\User;
require __DIR__ . '/../../vendor/autoload.php';

class UserService {
    protected $pdo;
    protected $userModel;

    public function __construct() {
        $this->pdo = \Database::connect();
        $this->userModel = new User();
    }
    public function userExists($userId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function createUser($username) {
        return $this->userModel->register($username);
    }
    
}
