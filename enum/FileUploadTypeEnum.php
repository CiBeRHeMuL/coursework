<?php

namespace app\enum;

use app\services\upload\checker\FileRequirementsChecker;
use app\services\upload\models\FileUploadTypeConfig;

enum FileUploadTypeEnum: string
{
    case PROJECT_VERSION = 'project_version';

    public function getConfig(): FileUploadTypeConfig
    {
        return match ($this) {
            self::PROJECT_VERSION => new FileUploadTypeConfig(
                'pv-files',
                new FileRequirementsChecker(
                    ['zip', 'tar'],
                ),
            ),
        };
    }
}
