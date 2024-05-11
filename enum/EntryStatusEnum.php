<?php

namespace app\enum;

use Yii;

enum EntryStatusEnum: string
{
    case Published = 'published';
    case Draft = 'draft';
    case Scheduled = 'scheduled';

    public function getName(): string
    {
        return match ($this) {
            self::Published => Yii::t('app', 'Опубликована'),
            self::Draft => Yii::t('app', 'Черновик'),
            self::Scheduled => Yii::t('app', 'Запланирована'),
        };
    }

    public function getTextColorClass(): BootstrapColorClassEnum
    {
        return match ($this) {
            self::Published => BootstrapColorClassEnum::Success,
            self::Draft => BootstrapColorClassEnum::Danger,
            self::Scheduled => BootstrapColorClassEnum::Primary,
        };
    }

    public function getAvailableStatuses(): array
    {
        return match ($this) {
            self::Published => [self::Published],
            self::Draft => self::cases(),
            self::Scheduled => [self::Scheduled, self::Published],
        };
    }
}
