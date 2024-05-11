<?php

namespace app\models\upload;

readonly class FileUploadResult
{
    public function __construct(
        private string $key,
        private bool $success,
        private string|null $message = null,
        private string|null $url = null,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): string|null
    {
        return $this->message;
    }

    public function getUrl(): string|null
    {
        return $this->url;
    }
}
