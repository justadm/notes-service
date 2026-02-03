<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Notes Service API',
    version: '1.0.0',
    description: "REST API для управления заметками.\n\nПример запроса:\ncurl -X POST http://localhost:8080/api/notes \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"title\":\"Test\",\"content\":\"Hello\"}'"
)]
#[OA\Server(url: '/')]
#[OA\Tag(name: 'Notes', description: 'Операции с заметками')]
#[OA\SecurityScheme(
    securityScheme: 'ApiToken',
    type: 'apiKey',
    name: 'X-API-Token',
    in: 'header',
    description: 'Статический токен доступа из переменной окружения API_TOKEN'
)]
#[OA\Schema(
    schema: 'Note',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Новая заметка'),
        new OA\Property(property: 'content', type: 'string', example: 'Содержимое'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-03 12:00:00'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-03 12:00:00')
    ]
)]
#[OA\Schema(
    schema: 'NoteCreateRequest',
    type: 'object',
    required: ['title'],
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 200),
        new OA\Property(property: 'content', type: 'string', maxLength: 5000)
    ]
)]
#[OA\Schema(
    schema: 'NoteUpdateRequest',
    type: 'object',
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 200),
        new OA\Property(property: 'content', type: 'string', maxLength: 5000)
    ]
)]
#[OA\Schema(
    schema: 'NoteResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'status', type: 'string', example: 'success'),
        new OA\Property(property: 'data', ref: '#/components/schemas/Note')
    ]
)]
#[OA\Schema(
    schema: 'NotesListResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'status', type: 'string', example: 'success'),
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Note')
        )
    ]
)]
#[OA\Schema(
    schema: 'MessageResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'status', type: 'string', example: 'success'),
        new OA\Property(property: 'message', type: 'string', example: 'Note deleted successfully')
    ]
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'status', type: 'string', example: 'error'),
        new OA\Property(property: 'message', type: 'string', example: 'Internal server error')
    ]
)]
#[OA\Schema(
    schema: 'ValidationErrorResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'status', type: 'string', example: 'error'),
        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            additionalProperties: new OA\AdditionalProperties(type: 'array', items: new OA\Items(type: 'string'))
        )
    ]
)]
#[OA\Get(
    path: '/api/notes',
    summary: 'Получить все заметки',
    tags: ['Notes'],
    security: [['ApiToken' => []]],
    responses: [
        new OA\Response(
            response: 401,
            description: 'Неавторизован',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        ),
        new OA\Response(
            response: 200,
            description: 'Список заметок',
            content: new OA\JsonContent(ref: '#/components/schemas/NotesListResponse')
        ),
        new OA\Response(
            response: 500,
            description: 'Внутренняя ошибка сервера',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        )
    ]
)]
#[OA\Post(
    path: '/api/notes',
    summary: 'Создать заметку',
    tags: ['Notes'],
    security: [['ApiToken' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/NoteCreateRequest')
    ),
    responses: [
        new OA\Response(
            response: 401,
            description: 'Неавторизован',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        ),
        new OA\Response(
            response: 201,
            description: 'Заметка создана',
            content: new OA\JsonContent(ref: '#/components/schemas/NoteResponse')
        ),
        new OA\Response(
            response: 422,
            description: 'Ошибка валидации',
            content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
        ),
        new OA\Response(
            response: 500,
            description: 'Внутренняя ошибка сервера',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        )
    ]
)]
#[OA\Get(
    path: '/api/notes/{id}',
    summary: 'Получить заметку по ID',
    tags: ['Notes'],
    security: [['ApiToken' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
    ],
    responses: [
        new OA\Response(
            response: 401,
            description: 'Неавторизован',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        ),
        new OA\Response(
            response: 200,
            description: 'Заметка',
            content: new OA\JsonContent(ref: '#/components/schemas/NoteResponse')
        ),
        new OA\Response(
            response: 404,
            description: 'Заметка не найдена',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        ),
        new OA\Response(
            response: 500,
            description: 'Внутренняя ошибка сервера',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        )
    ]
)]
#[OA\Put(
    path: '/api/notes/{id}',
    summary: 'Обновить заметку',
    tags: ['Notes'],
    security: [['ApiToken' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/NoteUpdateRequest')
    ),
    responses: [
        new OA\Response(
            response: 401,
            description: 'Неавторизован',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        ),
        new OA\Response(
            response: 200,
            description: 'Заметка обновлена',
            content: new OA\JsonContent(ref: '#/components/schemas/NoteResponse')
        ),
        new OA\Response(
            response: 404,
            description: 'Заметка не найдена',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        ),
        new OA\Response(
            response: 422,
            description: 'Ошибка валидации',
            content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
        ),
        new OA\Response(
            response: 500,
            description: 'Внутренняя ошибка сервера',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        )
    ]
)]
#[OA\Delete(
    path: '/api/notes/{id}',
    summary: 'Удалить заметку',
    tags: ['Notes'],
    security: [['ApiToken' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
    ],
    responses: [
        new OA\Response(
            response: 401,
            description: 'Неавторизован',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        ),
        new OA\Response(
            response: 200,
            description: 'Заметка удалена',
            content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
        ),
        new OA\Response(
            response: 404,
            description: 'Заметка не найдена',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        ),
        new OA\Response(
            response: 500,
            description: 'Внутренняя ошибка сервера',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
        )
    ]
)]
class OpenApi {}
