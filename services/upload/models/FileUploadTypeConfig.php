<?php

namespace app\services\upload\models;

use app\services\upload\checker\FileRequirementsCheckerInterface;

readonly class FileUploadTypeConfig
{
    public function __construct(
        private string $dir,
        private FileRequirementsCheckerInterface $checker,
    ) {
    }

    public function getDir(): string
    {
        return $this->dir;
    }

    public function getChecker(): FileRequirementsCheckerInterface
    {
        return $this->checker;
    }
}
