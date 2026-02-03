<?php

namespace App\Model;

class Note
{
    public ?int $id = null;
    public string $title;
    public ?string $content = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function __construct(array $data = [])
    {
        if (isset($data['id'])) $this->id = (int)$data['id'];
        if (isset($data['title'])) $this->title = $data['title'];
        if (isset($data['content'])) $this->content = $data['content'];
        if (isset($data['created_at'])) $this->created_at = $data['created_at'];
        if (isset($data['updated_at'])) $this->updated_at = $data['updated_at'];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content ?? '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
