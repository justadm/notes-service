<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Validator\NoteValidator;

class NoteValidatorTest extends TestCase
{
    private NoteValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new NoteValidator();
    }

    public function testValidateCreateSuccess(): void
    {
        $data = ['title' => 'Test Note', 'content' => 'Test Content'];
        $result = $this->validator->validateCreate($data);
        $this->assertTrue($result);
        $this->assertEmpty($this->validator->getErrors());
    }

    public function testValidateCreateEmptyTitle(): void
    {
        $data = ['content' => 'Test Content'];
        $result = $this->validator->validateCreate($data);
        $this->assertFalse($result);
        $this->assertArrayHasKey('title', $this->validator->getErrors());
    }

    public function testValidateCreateTitleTooLong(): void
    {
        $data = ['title' => str_repeat('a', 201), 'content' => 'Test'];
        $result = $this->validator->validateCreate($data);
        $this->assertFalse($result);
        $this->assertArrayHasKey('title', $this->validator->getErrors());
    }

    public function testValidateUpdateSuccess(): void
    {
        $data = ['title' => 'Updated Title'];
        $result = $this->validator->validateUpdate($data);
        $this->assertTrue($result);
    }

    public function testSanitize(): void
    {
        $data = [
            'title' => '  <script>alert("xss")</script>Test  ',
            'content' => '<b>Bold</b>',
            'extra' => 'Remove'
        ];
        $result = $this->validator->sanitize($data);
        $this->assertEquals('alert("xss")Test', $result['title']);
        $this->assertEquals('Bold', $result['content']);
        $this->assertArrayNotHasKey('extra', $result);
    }
}
