<?php

namespace app\helpers;

class HStrings
{
    public static function pluralForm(int $num, string $singleForm, string $dualForm, string $pluralForm): string
    {
        $num = abs($num);
        $num %= 100;
        if ($num >= 5 && $num <= 20) {
            return $pluralForm;
        }

        $num %= 10;
        if ($num === 1) {
            return $singleForm;
        } elseif ($num >= 2 && $num <= 4) {
            return $dualForm;
        }
        return $pluralForm;
    }

    /**
     * Обрезает строку до определенной длинны и добавляет ... в конце, если надо
     *
     * @param string $text
     * @param int $maxLength
     *
     * @return string
     */
    public static function crop(string $text, int $maxLength): string
    {
        if ($maxLength < mb_strlen($text)) {
            return trim(mb_substr($text, 0, $maxLength - 3)) . '...';
        }
        return $text;
    }
}
