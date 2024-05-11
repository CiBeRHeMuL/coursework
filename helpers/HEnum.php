<?php

namespace app\helpers;

use BackedEnum;
use Closure;
use UnitEnum;
use Yii;

enum HEnum
{
    /**
     * Массив значение - название из перечисления
     *
     * @param string $enumClass
     * @param Closure|array|null $filter фильтр для фильтрации значений, можно указать массив объектов перечисления или функцию,
     *      принимающую на вход объект перечисления
     * @param bool $withEmptyChoice
     *
     * @return array<string|int, string>
     */
    public static function getCasesList(string $enumClass, Closure|array|null $filter = null, bool $withEmptyChoice = true): array
    {
        $result = [];
        if (is_subclass_of($enumClass, UnitEnum::class)) {
            $filter = is_array($filter) ? fn(UnitEnum $e) => in_array($e, $filter, true) : $filter;
            $cases = $enumClass::cases();
            if ($filter !== null) {
                $cases = array_filter($cases, $filter);
            }
            if (method_exists($enumClass, 'getName')) {
                $result = array_combine(
                    array_map(fn(UnitEnum $e) => $e->value, $cases),
                    array_map(fn(UnitEnum $e) => $e->getName(), $cases),
                );
            } elseif (is_subclass_of($enumClass, BackedEnum::class)) {
                $result = array_combine(
                    array_map(fn(BackedEnum $e) => $e->value, $cases),
                    array_map(fn(BackedEnum $e) => $e->name, $cases),
                );
            } else {
                $names = array_map(fn(UnitEnum $e) => $e->name, $cases);
                $result = array_combine($names, $names);
            }
        }
        if ($withEmptyChoice) {
            $keys = [null, ...array_keys($result)];
            $result = array_combine($keys, [null => Yii::t('app', 'Не выбрано'), ...$result]);
        }
        return $result;
    }
}
