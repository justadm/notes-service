<?php

namespace App\Repository;

use App\Database;
use App\Model\Note;
use PDO;

class NoteRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM notes ORDER BY id DESC');
        $notes = [];
        
        while ($row = $stmt->fetch()) {
            $notes[] = new Note($row);
        }
        
        return $notes;
    }

    public function findById(int $id): ?Note
    {
        $stmt = $this->db->prepare('SELECT * FROM notes WHERE id = ?');
        $stmt->execute([$id]);
        
        $row = $stmt->fetch();
        return $row ? new Note($row) : null;
    }

    public function create(array $data): Note
    {
        $stmt = $this->db->prepare(
            'INSERT INTO notes (title, content) VALUES (?, ?)'
        );
        
        $stmt->execute([
            $data['title'],
            $data['content'] ?? ''
        ]);
        
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $data): ?Note
    {
        $setParts = [];
        $values = [];
        
        if (isset($data['title'])) {
            $setParts[] = 'title = ?';
            $values[] = $data['title'];
        }
        
        if (isset($data['content'])) {
            $setParts[] = 'content = ?';
            $values[] = $data['content'];
        }
        
        if (empty($setParts)) {
            return $this->findById($id);
        }
        
        $values[] = $id;
        
        $stmt = $this->db->prepare(
            'UPDATE notes SET ' . implode(', ', $setParts) . ' WHERE id = ?'
        );
        
        $stmt->execute($values);
        
        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM notes WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM notes WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
