<?php

namespace App\Controllers;
require __DIR__ . '/../../vendor/autoload.php';
use App\Services\GroupService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GroupController {
    protected $groupService;

    public function __construct() {
        $this->groupService = new GroupService();
    }

    public function createGroup(Request $request, Response $response) {
        $body = (string) $request->getBody();
        // Decode the JSON payload
        $data = json_decode($body, true);
        $name = $data['name'] ?? '';
        $group = $this->groupService->createGroup($name);

        if ($group) {
            $response->getBody()->write(json_encode($group));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Group name is taken']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
    
    public function getAllGroups(Request $request, Response $response) {
    
        // Fetch all groups
        $groups = $this->groupService->getAllGroups();
        
        // Send a JSON response
        $response->getBody()->write(json_encode($groups));
        
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function getMessages(Request $request, Response $response, array $args) {
        $groupId = $args['id'];
        $messages = $this->groupService->getMessages($groupId);
    
        $response->getBody()->write(json_encode($messages));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
    
}
