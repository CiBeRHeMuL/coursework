<?php

namespace app\db;

use app\helpers\HDates;
use BackedEnum;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use UnitEnum;
use yii\base\InvalidArgumentException;
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

    public function load($data, $formName = null): bool
    {
        $scope = $formName === null ? $this->formName() : $formName;
        $data = $scope === '' ? $data : $data[$scope] ?? [];
        if (!empty($data)) {
            foreach ($data as $name => &$value) {
                [$converted, $newValue] = $this->tryConvertDbValueToPhpValue($name, $value);
                if ($converted) {
                    $value = $newValue;
                }
            }
            $this->setAttributes($data);
            return true;
        }
        return false;
    }

    public function __get($name)
    {
        $value = $this->getAttribute($name);
        [$completed, $newValue] = $this->tryConvertDbValueToPhpValue($name, $value);
        return $completed ? $newValue : parent::__get($name);
    }

    private function tryConvertDbValueToPhpValue(string $name, $value): array
    {
        /**
         * Пытаемся получить поле как перечисление
         */
        $enum = $this->getFieldEnum($name);
        if ($enum) {
            return [true, $this->dbValueToPhpEnum($enum, $value)];
        }
        /**
         * Пытаемся получить поле как время
         */
        $dateTimeClass = $this->getDateTimeFieldPhpType($name);
        if ($dateTimeClass) {
            return [true, $this->dbValueToPhpDateTime($dateTimeClass, $value)];
        }
        return [false, null];
    }

    public function __set($name, $value): void
    {
        /**
         * Пытаемся установить поле как перечисление
         */
        $enum = $this->getFieldEnum($name);
        if ($enum) {
            $value = $this->phpEnumToDbValue($enum, $value);
        }
        /**
         * Пытаемся установить поле как время
         */
        $dateTimeFormat = $this->getDateTimeFieldDbFormat($name);
        if ($dateTimeFormat) {
            $value = $this->phpDateTimeToDbValue($dateTimeFormat, $value);
        }
        parent::__set($name, $value);
    }

    public function dbValueToPhpEnum(string $enumClass, string|int|null $value): UnitEnum|null
    {
        if ($value) {
            if (is_subclass_of($enumClass, BackedEnum::class)) {
                return $enumClass::from($value);
            } elseif (is_subclass_of($enumClass, UnitEnum::class)) {
                return $enumClass::$$value;
            } else {
                throw new InvalidArgumentException('Invalid enum class');
            }
        }
        return null;
    }

    public function phpEnumToDbValue(string $enumClass, UnitEnum|null $value): int|string|null
    {
        if ($value !== null) {
            if (is_subclass_of($enumClass, BackedEnum::class)) {
                return $value->value;
            } elseif (is_subclass_of($enumClass, UnitEnum::class)) {
                return $value->name;
            }
        }
        return null;
    }

    public function dbValueToPhpDateTime(string $class, string|null $value): DateTimeInterface|null
    {
        if ($value) {
            if (is_a($class, DateTime::class, true)) {
                return new DateTime($value);
            } elseif (is_a($class, DateTimeImmutable::class, true)) {
                return new DateTimeImmutable($value);
            }
        }
        return null;
    }

    public function phpDateTimeToDbValue(string $format, DateTimeInterface|null $time): string|null
    {
        return $time?->format($format);
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

    public function getRawAttribute(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return parent::__get($name);
    }

    public function getOldAttribute($name)
    {
        $value = parent::getOldAttribute($name);
        [$completed, $newValue] = $this->tryConvertDbValueToPhpValue($name, $value);
        return $completed ? $newValue : $value;
    }

    public function getOldAttributes(): array
    {
        $attributes = parent::getOldAttributes();
        array_walk(
            $attributes,
            fn(&$value, $key) => $value = $this->tryConvertDbValueToPhpValue($key, $value),
        );
        return $attributes;
    }
}
