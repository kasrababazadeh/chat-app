<?php

use Tuupola\Middleware\CorsMiddleware;
use Slim\Factory\AppFactory;
use App\Controllers\UserController;
use App\Controllers\GroupController;
use App\Controllers\MessageController;

// Autoload dependencies
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../config/database.php';

// Create App
$app = AppFactory::create();


// CORS Middleware setup
$app->add(new CorsMiddleware([
    "origin" => ["http://localhost:3000"],
    "methods" => ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
    "headers.allow" => ["Content-Type", "X-Requested-With"],
    "headers.expose" => [],
    "credentials" => true,
    "maxAge" => 86400,
]));

// Define Routes
$app->post('/users', [UserController::class, 'createUser']);
$app->post('/groups', [GroupController::class, 'createGroup']);
$app->get('/grouplists', [GroupController::class, 'getAllGroups']);
$app->get('/groups/{id}/messageslist', [GroupController::class, 'getMessages']);
$app->post('/groups/{group_id}/messages', [MessageController::class, 'sendMessage']);