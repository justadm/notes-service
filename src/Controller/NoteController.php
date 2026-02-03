<?php

namespace App\Controller;

use App\Service\NoteService;

class NoteController
{
    private NoteService $service;

    public function __construct()
    {
        $this->service = new NoteService();
    }

    public function handleRequest(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Token');
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        if (!$this->isAuthorized()) {
            $this->sendResponse($this->buildUnauthorizedResponse(), 401);
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        $path = parse_url($uri, PHP_URL_PATH);
        $path = str_replace('/api/notes', '', $path);
        $path = trim($path, '/');

        try {
            switch ($method) {
                case 'GET':
                    $this->handleGet($path);
                    break;
                case 'POST':
                    $this->handlePost($path);
                    break;
                case 'PUT':
                    $this->handlePut($path);
                    break;
                case 'DELETE':
                    $this->handleDelete($path);
                    break;
                default:
                    $this->sendResponse(['error' => 'Method not allowed'], 405);
            }
        } catch (\Exception $e) {
            $this->sendResponse([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function handleGet(string $path): void
    {
        if (empty($path)) {
            $result = $this->service->getAll();
            $this->sendResponse($result);
            return;
        }

        if (is_numeric($path)) {
            $result = $this->service->getById((int)$path);
            $code = $result['code'] ?? ($result['success'] ? 200 : 400);
            $this->sendResponse($result, $code);
            return;
        }

        $this->sendResponse(['error' => 'Not found'], 404);
    }

    private function handlePost(string $path): void
    {
        if (empty($path)) {
            $data = $this->getJsonInput();
            $result = $this->service->create($data);
            $code = $result['code'] ?? ($result['success'] ? 201 : 400);
            $this->sendResponse($result, $code);
            return;
        }

        $this->sendResponse(['error' => 'Not found'], 404);
    }

    private function handlePut(string $path): void
    {
        if (is_numeric($path)) {
            $data = $this->getJsonInput();
            $result = $this->service->update((int)$path, $data);
            $code = $result['code'] ?? ($result['success'] ? 200 : 400);
            $this->sendResponse($result, $code);
            return;
        }

        $this->sendResponse(['error' => 'Not found'], 404);
    }

    private function handleDelete(string $path): void
    {
        if (is_numeric($path)) {
            $result = $this->service->delete((int)$path);
            $code = $result['code'] ?? ($result['success'] ? 200 : 400);
            $this->sendResponse($result, $code);
            return;
        }

        $this->sendResponse(['error' => 'Not found'], 404);
    }

    private function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        return $data ?? [];
    }

    private function sendResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);

        $response = [
            'status' => ($data['success'] ?? false) ? 'success' : 'error'
        ];

        if (isset($data['data'])) {
            $response['data'] = $data['data'];
        }

        if (isset($data['message'])) {
            $response['message'] = $data['message'];
        }

        if (isset($data['error'])) {
            $response['message'] = $data['error'];
        }

        if (isset($data['errors'])) {
            $response['errors'] = $data['errors'];
        }

        if (isset($data['details'])) {
            $response['details'] = $data['details'];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    private function buildUnauthorizedResponse(): array
    {
        $expected = getenv('API_TOKEN') ?: 'change-me';
        $details = [
            'header' => 'X-API-Token',
            'example' => 'X-API-Token: change-me'
        ];

        if ((getenv('APP_ENV') ?: '') === 'dev') {
            $details['debug_token'] = $expected;
        }

        return [
            'error' => 'Unauthorized',
            'message' => 'Missing or invalid API token',
            'details' => $details
        ];
    }

    private function isAuthorized(): bool
    {
        $expected = getenv('API_TOKEN') ?: 'change-me';
        $headers = function_exists('getallheaders') ? getallheaders() : [];

        $token = '';
        if (isset($headers['X-API-Token'])) {
            $token = $headers['X-API-Token'];
        } elseif (isset($headers['x-api-token'])) {
            $token = $headers['x-api-token'];
        } else {
            $token = $_SERVER['HTTP_X_API_TOKEN'] ?? '';
        }

        return is_string($token) && hash_equals($expected, $token);
    }
}
