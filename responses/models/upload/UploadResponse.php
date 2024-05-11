<?php

namespace app\responses\models\upload;

class UploadResponse
{
    /**
     * @param bool $success
     * @param FileUploadResponse[] $results
     */
    public function __construct(
        public bool $success,
        public array $results,
    ) {
    }
}
