<?php

namespace app\helpers;

use DateTimeInterface;
use Yii;
use yii\bootstrap5\Html;

class HHtml
{
    public static function dateUi(DateTimeInterface $time): string
    {
        return self::tooltipSpan(
            HDates::prettyUi($time),
            $time->format('Y-m-d H:i:s'),
        );
    }

    public static function tooltipSpan(string $text, string $tooltip, array $options = []): string
    {
        return Html::tag(
            'span',
            $text,
            array_merge(
                [
                    'data-bs-placement' => 'bottom',
                    'data-bs-custom-style' => 'font-size: 0.8rem;',
                ],
                $options,
                [
                    'data-bs-title' => $tooltip,
                    'data-bs-toggle' => 'tooltip',
                ],
            ),
        );
    }

    public static function formButtonGroup(
        bool $update,
        string|null $createTitle = null,
        string|null $updateTitle = null,
        string|null $resetTitle = null,
        array $options = []
    ): string {
        return Html::tag(
            'div',
            Html::resetButton($resetTitle ?? Yii::t('app', 'Сбросить'), ['class' => 'btn btn-outline-secondary']) . Html::submitButton(
                $update
                    ? $updateTitle ?? Yii::t('app', 'Сохранить')
                    : $createTitle ?? Yii::t('app', 'Создать'),
                ['class' => $update ? 'btn btn-primary' : 'btn btn-success'],
            ),
            array_merge($options, ['class' => 'btn-group']),
        );
    }

    public static function avatarFromName(string $name, bool $small = false): string
    {
        $hash = 0;
        $color = '#';
        for ($i = 0; $i < strlen($name); $i++) {
            $hash = ord(substr($name, $i, 1)) + (($hash << 5) - $hash);
        }
        for ($i = 0; $i < 3; $i++) {
            $value = ($hash >> ($i * 8)) & 0xFF;
            $c = '00' . dechex($value);
            $color .= substr($c, strlen($c) - 1, 2);
        }

        return Html::tag(
            'div',
            Html::tag('span', mb_substr($name, 0, 1)),
            [
                'style' => "background-color: $color;",
                'class' => 'avatar' . ($small ? '-sm' : ''),
            ],
        );
    }

    public static function avatar(string $avatar, bool $small = false): string
    {
        return Html::img($avatar, ['class' => 'avatar' . ($small ? '-sm' : '')]);
    }
}
