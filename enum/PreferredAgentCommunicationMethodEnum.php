<?php

namespace app\enum;

use Yii;

enum PreferredAgentCommunicationMethodEnum: int
{
    case Phone = 1;
    case Email = 2;
    case Telegram = 3;

    public function getName(): string
    {
        return match ($this) {
            self::Phone => Yii::t('app', 'Мобильный телефон'),
            self::Email => Yii::t('app', 'Почта'),
            self::Telegram => Yii::t('app', 'Телеграм'),
        };
    }

    public function getShortName(): string
    {
        return match ($this) {
            self::Phone => Yii::t('app', 'Тел'),
            self::Email => Yii::t('app', 'Email'),
            self::Telegram => Yii::t('app', 'Tg'),
        };
    }

    public function getLinkPrefix(): string
    {
        return match ($this) {
            self::Phone => 'tel:',
            self::Email => 'mailto:',
            self::Telegram => 'https://t.me/',
        };
    }
}
