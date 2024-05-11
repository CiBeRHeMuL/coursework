<?php

namespace app\enum;

use Yii;

enum EntryTypeEnum: string
{
    case ProjectLaunch = 'project_launch';
    case ProjectUpdate = 'project_update';
    case ProjectWarning = 'project_warning';

    public function getName(): string
    {
        return match ($this) {
            self::ProjectLaunch => Yii::t('app', 'Запуск'),
            self::ProjectUpdate => Yii::t('app', 'Обновление'),
            self::ProjectWarning => Yii::t('app', 'Объявление'),
        };
    }

    public function getTextColorClass(): BootstrapColorClassEnum
    {
        return match ($this) {
            self::ProjectLaunch => BootstrapColorClassEnum::Success,
            self::ProjectUpdate => BootstrapColorClassEnum::Primary,
            self::ProjectWarning => BootstrapColorClassEnum::Warning,
        };
    }
}
