<?php

namespace app\db;

use yii\db\pgsql\Schema;

class PgSchema extends Schema
{
    public $columnSchemaClass = PgColumnSchema::class;

    protected function getColumnPhpType($column): string
    {
        if ($column->type == self::TYPE_DECIMAL) {
            return 'float';
        }
        return parent::getColumnPhpType($column);
    }
}
