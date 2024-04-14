<?php

error_reporting(E_ALL & ~E_DEPRECATED);

if (file_exists(__DIR__ . '/../config/constants-local.php')) {
    require_once(__DIR__ . '/../config/constants-local.php');
}

defined('YII_DEBUG') or define('YII_DEBUG', !!getenv('YII_DEBUG') ?: false);
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
if (file_exists(__DIR__ . '/../config/web-local.php')) {
    $config = yii\helpers\ArrayHelper::merge(
        $config,
        require(__DIR__ . '/../config/web-local.php'),
    );
}

(new yii\web\Application($config))->run();
