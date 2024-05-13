<?php

// Load Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

use API\Controllers\EventController;
use API\Controllers\UserController;

// Define routes
$route = $_GET['route'] ?? '';

switch ($route) {
    case 'events':
        $eventController = new EventController();
        // Handle HTTP methods
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            echo $eventController->index();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo $eventController->store();
        }
        // Add other HTTP methods as needed (e.g., PUT, PATCH, DELETE)
        break;
    case 'events/{id}':
        $eventController = new EventController();
        // Handle HTTP methods
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET['id'] ?? null;
            echo $eventController->show($id);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $id = $_GET['id'] ?? null;
            echo $eventController->update($id);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $id = $_GET['id'] ?? null;
            echo $eventController->destroy($id);
        }
        // Add other HTTP methods as needed
        break;
    case 'users':
        $userController = new UserController();
        // Handle HTTP methods
        // Implement similar logic as above for users
        break;
    default:
        // Handle unsupported routes
        break;
}
