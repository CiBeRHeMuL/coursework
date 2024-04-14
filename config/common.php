<?php

$params = require __DIR__ . '/params.php';
if (file_exists(__DIR__ . '/params-local.php')) {
    $params = yii\helpers\ArrayHelper::merge(
        $params,
        require(__DIR__ . '/params-local.php'),
    );
}
$db = require __DIR__ . '/db.php';
if (file_exists(__DIR__ . '/db-local.php')) {
    $db = yii\helpers\ArrayHelper::merge(
        $db,
        require(__DIR__ . '/db-local.php'),
    );
}
$container = require __DIR__ . '/container.php';
if (file_exists(__DIR__ . '/container-local.php')) {
    $container = yii\helpers\ArrayHelper::merge(
        $container,
        require(__DIR__ . '/container-local.php'),
    );
}

$common = [
    'params' => $params,
    'db' => $db,
    'container' => $container,
];

return $common;
