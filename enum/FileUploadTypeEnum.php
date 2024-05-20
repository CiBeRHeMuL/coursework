<?php

namespace app\enum;

use app\services\upload\checker\FileRequirementsChecker;
use app\services\upload\checker\ImageRequirementsChecker;
use app\services\upload\models\FileUploadTypeConfig;

enum FileUploadTypeEnum: string
{
    case PROJECT_VERSION = 'project_version';
    case USER_AVATAR = 'user_avatar';
    case PROJECT_LOGO = 'project_logo';

    public function getConfig(): FileUploadTypeConfig
    {
        return match ($this) {
            self::PROJECT_VERSION => new FileUploadTypeConfig(
                'pv-files',
                new FileRequirementsChecker(
                    ['zip', 'tar'],
                ),
            ),
            self::USER_AVATAR => new FileUploadTypeConfig(
                'user-avatar',
                new ImageRequirementsChecker(
                    ['jpeg', 'jpg', 'png'],
                    minWidth: 100,
                    minHeight: 100,
                    ratio: 1,
                    ratioString: '1x1',
                ),
            ),
            self::PROJECT_LOGO => new FileUploadTypeConfig(
                'pr-logo',
                new ImageRequirementsChecker(
                    ['jpeg', 'jpg', 'png'],
                    minWidth: 200,
                    minHeight: 200,
                    ratio: 1,
                    ratioString: '1x1',
                ),
            ),
        };
    }

    /**
     * @return string[]
     */
    public function getExtensions(): array
    {
        return match ($this) {
            self::PROJECT_VERSION => ['.tar', '.zip'],
            self::USER_AVATAR => ['.jpeg', '.jpg', '.png'],
            self::PROJECT_LOGO => ['.jpeg', '.jpg', '.png'],
        };
    }
}
