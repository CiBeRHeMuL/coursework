<?php

return [
    'class' => 'yii\db\Connection',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'queryCacheDuration' => 300,
    'schemaMap' => [
        'pgsql' => 'app\db\PgSchema',
    ],

    'dsn' => getenv('PG_DSN'),
    'username' => getenv('PG_USER'),
    'password' => getenv('PG_PASSWORD'),
    'charset' => 'utf8',
];
