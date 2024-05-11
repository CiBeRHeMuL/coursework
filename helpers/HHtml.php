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
}
