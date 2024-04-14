<?php

namespace app\db;

use app\helpers\HDates;
use BackedEnum;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use UnitEnum;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 */
abstract class AbstractPgModel extends ActiveRecord
{
    public const PROPERTY_DATETIME_FORMAT = 'Y-m-d H:i:s';
    public const PROPERTY_DATE_FORMAT = 'Y-m-d';

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => fn() => HDates::now(),
            ],
        ];
    }

    public function __get($name)
    {
        $value = $this->getAttribute($name);
        /**
         * Пытаемся получить поле как перечисление
         */
        $enum = $this->getFieldEnum($name);
        if ($enum) {
            if ($value !== null) {
                if (is_subclass_of($enum, BackedEnum::class)) {
                    return $enum::from($value);
                } elseif (is_subclass_of($enum, UnitEnum::class)) {
                    return $enum::$$value;
                }
            }
            return $value;
        }
        /**
         * Пытаемся получить поле как время
         */
        $dateTimeClass = $this->getDateTimeFieldPhpType($name);
        if ($dateTimeClass) {
            if ($value !== null) {
                if (is_subclass_of($dateTimeClass, DateTime::class)) {
                    return new DateTime($value);
                } elseif (is_subclass_of($dateTimeClass, DateTimeImmutable::class)) {
                    return new DateTimeImmutable($value);
                }
            }
            return null;
        }
        return parent::__get($name);
    }

    public function __set($name, $value): void
    {
        /**
         * Пытаемся установить поле как перечисление
         */
        $enum = $this->getFieldEnum($name);
        if ($enum) {
            if ($value !== null) {
                if (is_subclass_of($enum, BackedEnum::class)) {
                    $value = $value->value;
                } elseif (is_subclass_of($enum, UnitEnum::class)) {
                    $value = $value->name;
                }
            }
        }
        /**
         * Пытаемся установить поле как время
         */
        $dateTimeFormat = $this->getDateTimeFieldDbFormat($name);
        if ($dateTimeFormat) {
            /** @var DateTimeInterface|null $value */
            if ($value !== null) {
                $value = $value->format($dateTimeFormat);
            }
        }
        parent::__set($name, $value);
    }

    /**
     * Класс перечисления для поля
     *
     * @param string $name
     *
     * @return class-string|null
     */
    public function getFieldEnum(string $name): string|null
    {
        return $this->getFieldsEnum()[$name] ?? null;
    }

    /**
     * Поля, значения которых - перечисления
     * @return array<string, string>
     */
    public function getFieldsEnum(): array
    {
        return [];
    }

    /**
     * Класс времени для поля
     *
     * @param string $name
     *
     * @return class-string|null
     */
    public function getDateTimeFieldPhpType(string $name): string|null
    {
        return ($this->getDateTimeFieldsType()[$name] ?? [])[0] ?? null;
    }

    /**
     * Класс времени для поля
     *
     * @param string $name
     *
     * @return class-string|null
     */
    public function getDateTimeFieldDbFormat(string $name): string|null
    {
        return ($this->getDateTimeFieldsType()[$name] ?? [])[1] ?? null;
    }

    /**
     * Поля, значения которых - дата
     * Вид массива:
     * [
     *     'created_at' => [DateTimeImmutable::class, 'Y-m-d H:i:s'],
     * ]
     * @return array<string, string[]>
     */
    public function getDateTimeFieldsType(): array
    {
        return [
            'created_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'updated_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
        ];
    }
}
