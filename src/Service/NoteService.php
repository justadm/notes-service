<?php

namespace App\Service;

use App\Repository\NoteRepository;
use App\Validator\NoteValidator;

class NoteService
{
    private NoteRepository $repository;
    private NoteValidator $validator;

    public function __construct()
    {
        $this->repository = new NoteRepository();
        $this->validator = new NoteValidator();
    }

    public function getAll(): array
    {
        try {
            $notes = $this->repository->findAll();
            return [
                'success' => true,
                'data' => array_map(fn($note) => $note->toArray(), $notes)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to fetch notes: ' . $e->getMessage()
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $note = $this->repository->findById($id);

            if (!$note) {
                return [
                    'success' => false,
                    'error' => 'Note not found',
                    'code' => 404
                ];
            }

            return [
                'success' => true,
                'data' => $note->toArray()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to fetch note: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        $data = $this->validator->sanitize($data);

        if (!$this->validator->validateCreate($data)) {
            return [
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $this->validator->getErrors(),
                'code' => 422
            ];
        }

        try {
            $note = $this->repository->create($data);

            return [
                'success' => true,
                'data' => $note->toArray(),
                'code' => 201
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to create note: ' . $e->getMessage()
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        if (!$this->repository->exists($id)) {
            return [
                'success' => false,
                'error' => 'Note not found',
                'code' => 404
            ];
        }

        $data = $this->validator->sanitize($data);

        if (!$this->validator->validateUpdate($data)) {
            return [
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $this->validator->getErrors(),
                'code' => 422
            ];
        }

        if (empty($data)) {
            return [
                'success' => false,
                'error' => 'No data to update',
                'code' => 422
            ];
        }

        try {
            $note = $this->repository->update($id, $data);

            return [
                'success' => true,
                'data' => $note->toArray()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to update note: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        if (!$this->repository->exists($id)) {
            return [
                'success' => false,
                'error' => 'Note not found',
                'code' => 404
            ];
        }

        try {
            $this->repository->delete($id);

            return [
                'success' => true,
                'message' => 'Note deleted successfully'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to delete note: ' . $e->getMessage()
            ];
        }
    }
}
