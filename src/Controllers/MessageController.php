<?php

namespace App\Controllers;

use App\Services\MessageService;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessageController
{
    protected $messageService;
    protected $userService;

    public function __construct()
    {
        $this->messageService = new MessageService();
        $this->userService = new UserService();
    }

    public function sendMessage(Request $request, Response $response, array $args): Response
    {
        $groupId = $args['group_id'];
        $body = json_decode($request->getBody(), true);

        $userId = $body['user_id'] ?? null;
        $message = $body['message'] ?? null;

        if (!$this->userService->userExists($userId)) {
            $response->getBody()->write('User not found');
            return $response->withStatus(404);
        }

        $messageId = $this->messageService->sendMessage($groupId, $userId, $message);

        $response = $response->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
                     ->withHeader('Access-Control-Allow-Methods', 'POST, OPTIONS')
                     ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
                     ->withHeader('Access-Control-Allow-Credentials', 'true');

        if ($messageId) {
            $response->getBody()->write(json_encode(['message_id' => $messageId]));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write('Failed to send message');
            return $response->withStatus(400);
        }
    }

}
