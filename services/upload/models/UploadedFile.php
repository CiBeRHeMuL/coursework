<?php

namespace app\services\upload\models;

readonly class UploadedFile
{
    public function __construct(
        private string $key,
        private int $error,
        private string $full_path,
        private string $name,
        private int $size,
        private string $tmp_name,
        private string $type,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getFullPath(): string
    {
        return $this->full_path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getTmpName(): string
    {
        return $this->tmp_name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getExtension(): string
    {
        return strtolower(pathinfo($this->name, PATHINFO_EXTENSION));
    }
}
