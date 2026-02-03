<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\NoteController;

$uri = $_SERVER['REQUEST_URI'];

if (strpos($uri, '/api/notes') === 0) {
    $controller = new NoteController();
    $controller->handleRequest();
} else {
    if (file_exists(__DIR__ . '/../frontend/dist/index.html')) {
        header('Content-Type: text/html; charset=utf-8');
        readfile(__DIR__ . '/../frontend/dist/index.html');
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Not found'], JSON_PRETTY_PRINT);
    }
}
