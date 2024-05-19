<?php

namespace app\services\upload\checker;

use app\services\upload\models\UploadedFile;
use Yii;

class FileRequirementsChecker implements FileRequirementsCheckerInterface
{
    /**
     * @var string[] $errors
     */
    private array $errors = [];

    public function __construct(
        private readonly array|null $extensions = null,
    ) {
    }

    public function check(UploadedFile $file): bool
    {
        $this->errors = [];
        if ($this->extensions !== null && !in_array($file->getExtension(), $this->extensions, true)) {
            $this->errors[] = Yii::t('app', 'Неизвестное расширение');
            return false;
        }
        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getLastError(): string|null
    {
        return $this->errors ? reset($this->errors) : null;
    }
}
