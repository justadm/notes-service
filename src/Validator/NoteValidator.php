<?php

namespace App\Validator;

class NoteValidator
{
    private array $errors = [];

    public function validateCreate(array $data): bool
    {
        $this->errors = [];

        if (empty($data['title'])) {
            $this->errors['title'][] = 'Title is required';
        }

        if (!empty($data['title']) && mb_strlen($data['title']) > 200) {
            $this->errors['title'][] = 'Title must not exceed 200 characters';
        }

        if (!empty($data['title']) && mb_strlen(trim($data['title'])) < 1) {
            $this->errors['title'][] = 'Title must be at least 1 character';
        }

        if (isset($data['content']) && mb_strlen($data['content']) > 5000) {
            $this->errors['content'][] = 'Content must not exceed 5000 characters';
        }

        return empty($this->errors);
    }

    public function validateUpdate(array $data): bool
    {
        $this->errors = [];

        if (isset($data['title'])) {
            if (empty($data['title'])) {
                $this->errors['title'][] = 'Title cannot be empty';
            }

            if (!empty($data['title']) && mb_strlen($data['title']) > 200) {
                $this->errors['title'][] = 'Title must not exceed 200 characters';
            }

            if (!empty($data['title']) && mb_strlen(trim($data['title'])) < 1) {
                $this->errors['title'][] = 'Title must be at least 1 character';
            }
        }

        if (isset($data['content']) && mb_strlen($data['content']) > 5000) {
            $this->errors['content'][] = 'Content must not exceed 5000 characters';
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function sanitize(array $data, array $allowedFields = ['title', 'content']): array
    {
        $sanitized = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $sanitized[$field] = strip_tags(trim($data[$field]));
            }
        }

        return $sanitized;
    }
}
