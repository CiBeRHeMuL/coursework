<?php

namespace app\db;

use BackedEnum;
use DateTimeInterface;
use UnitEnum;
use yii\db\pgsql\ColumnSchema;
use yii\db\pgsql\Schema;
use yii\db\Schema as SchemaAlias;

class PgColumnSchema extends ColumnSchema
{
    protected function phpTypecastValue($value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($this->type) {
            SchemaAlias::TYPE_JSON, Schema::TYPE_JSONB => json_decode($value, true),
            default => parent::phpTypecastValue($value),
        };
    }

    protected function typecast($value)
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        } elseif ($value instanceof UnitEnum) {
            $value = $value->name;
        }
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s.u');
        }
        return parent::typecast($value);
    }
}
