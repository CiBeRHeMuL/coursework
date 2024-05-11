<?php

namespace app\responses\models\upload;

class FileUploadResponse
{
    public function __construct(
        public string $key,
        public bool $success,
        public string|null $message = null,
        public string|null $uploaded_url = null,
    ) {
    }
}
