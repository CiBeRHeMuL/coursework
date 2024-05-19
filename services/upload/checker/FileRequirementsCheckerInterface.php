<?php

namespace app\services\upload\checker;

use app\services\upload\models\UploadedFile;

interface FileRequirementsCheckerInterface
{
    /**
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function check(UploadedFile $file): bool;

    /**
     * @return string[]
     */
    public function getErrors(): array;

    public function getLastError(): string|null;
}
