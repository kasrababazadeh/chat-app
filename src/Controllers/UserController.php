<?php

namespace App\Controllers;
require __DIR__ . '/../../vendor/autoload.php';
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
    protected $userService;

    public function __construct() {
        $this->userService = new UserService();
    }
    public function getUserByToken(Request $request, Response $response) {
        $token = $request->getHeaderLine('Authorization');
        $user = $this->userService->getUserByToken($token);
        
        if ($user) {
            return $response->withJson($user);
        }
        
        return $response->withStatus(404)->write('User not found');
    }
    
    public function userExists($userId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function createUser(Request $request, Response $response) {
        // Get the raw body content
        $body = (string) $request->getBody();
        // Decode the JSON payload
        $data = json_decode($body, true);
    
        $username = $data['username'] ?? '';
        $user = $this->userService->createUser($username);
    
        if ($user) {
            $response->getBody()->write(json_encode(['token' => $user['token'], 'user_id' => $user['id']]));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Username is taken']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
    
}
