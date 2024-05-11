<?php

namespace app\enum;

use Yii;

enum ProjectStatusEnum: string
{
    case Active = 'active';
    case Draft = 'draft';
    case Inactive = 'inactive';

    public static function getDefaultAvailableStatuses(): array
    {
        return [self::Draft, self::Inactive];
    }

    public function getName(): string
    {
        return match ($this) {
            self::Active => Yii::t('app', 'Активeн'),
            self::Inactive => Yii::t('app', 'Неактивен'),
            self::Draft => Yii::t('app', 'Черновик'),
        };
    }

    public function getAvailableStatuses(): array
    {
        return match ($this) {
            self::Active => [self::Active, self::Inactive],
            self::Inactive => [self::Inactive, self::Active],
            self::Draft => [self::Active, self::Inactive, self::Draft],
        };
    }

    public function getTextColorClass(): BootstrapColorClassEnum
    {
        return match ($this) {
            self::Active => BootstrapColorClassEnum::Success,
            self::Draft => BootstrapColorClassEnum::Secondary,
            self::Inactive => BootstrapColorClassEnum::Danger,
        };
    }
}
