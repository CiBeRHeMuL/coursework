<?php

namespace app\helpers;

use DateTimeImmutable;
use DateTimeInterface;
use Yii;

class HDates
{
    public const MINUTE = 60;
    public const HOUR = 3600;
    public const DAY = 86400;
    public const WEEK = 604800;
    public const MONTH = 2628000;
    public const YEAR = 31536000;

    public static function translateMonth(string $month, bool $genetive = false): string
    {
        $isUpperCase = ctype_upper($month);
        $isFirstUpperCase = !$isUpperCase && ctype_upper($month[0]);
        $month = strtolower($month);
        $result = match ($month) {
            'january' => 'январь',
        };
        if ($isUpperCase) {
            $result = mb_strtoupper($result);
        } elseif ($isFirstUpperCase) {
            $result = mb_strtoupper(mb_substr($result, 0, 1)) . mb_substr($result, 1);
        }
        return $result;
    }

    public static function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }

    public static function prettyUi(DateTimeInterface $time, bool $short = false): string
    {
        $now = new DateTimeImmutable(timezone: $time->getTimezone());
        $nowTimestamp = $now->getTimestamp();
        $timeTimestamp = $time->getTimestamp();
        if ($nowTimestamp >= $timeTimestamp) {
            $diff = $nowTimestamp - $timeTimestamp;
            if ($diff < self::MINUTE) {
                return Yii::t('app', 'Только что');
            } elseif ($diff < self::MINUTE * 5) {
                return Yii::t('app', '5 минут назад');
            } elseif ($diff < self::MINUTE * 15) {
                return Yii::t('app', '15 минут назад');
            } elseif ($diff < self::MINUTE * 30) {
                return Yii::t('app', 'Полчаса назад');
            } elseif ($diff < self::DAY) {
                $hoursCount = intdiv($diff, self::HOUR) + 1;
                if ($hoursCount > 1 && $hoursCount < 24) {
                    return Yii::t(
                        'app',
                        '{num} {form} назад',
                        [
                            'num' => $hoursCount,
                            'form' => HStrings::pluralForm(
                                $hoursCount,
                                Yii::t('app', 'час'),
                                Yii::t('app', 'часа'),
                                Yii::t('app', 'часов'),
                            ),
                        ],
                    );
                } elseif ($hoursCount === 1) {
                    return Yii::t('app', 'Час назад');
                }
                return Yii::t('app', 'Сутки назад');
            } elseif ($diff < self::MONTH) {
                $monthDiff = $now->diff($time)->m;
                $daysCount = intdiv($diff, self::DAY) + 1;
                if ($monthDiff === 1 || $daysCount > 30) {
                    return Yii::t('app', 'Месяц назад');
                } elseif ($daysCount > 1 && $daysCount < 30) {
                    return Yii::t(
                        'app',
                        '{num} {form} назад',
                        [
                            'num' => $daysCount,
                            'form' => HStrings::pluralForm(
                                $daysCount,
                                Yii::t('app', 'день'),
                                Yii::t('app', 'дня'),
                                Yii::t('app', 'дней'),
                            ),
                        ],
                    );
                } elseif ($daysCount === 1) {
                    return Yii::t('app', 'Сутки назад');
                }
            }
        }
        return Yii::t('app', 'Очень давно');
    }
}
