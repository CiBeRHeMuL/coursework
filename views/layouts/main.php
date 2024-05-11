<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php
$this->beginBody() ?>

<header id="header" class="py-3 bg-light">
    <div class="d-flex align-items-center container py-0">
        <div class="menu__home">
            <a href="/" class="text-muted fs-5 link-opacity-hover"><?= Yii::t('app', 'Домой'); ?></a>
        </div>
        <div class="menu">
            <a class="text-muted link-opacity-hover" href="/projects"><?= Yii::t('app', 'Проекты'); ?></a>
            <a class="text-muted link-opacity-hover" href="/entries"><?= Yii::t('app', 'Новости'); ?></a>
            <a class="text-muted link-opacity-hover" href="/project-agents"><?= Yii::t('app', 'Агенты'); ?></a>
        </div>
        <div class="menu-mobile">
            <div class="dropdown">
                <button class="text-muted menu-mobile__button dropdown-toggle bg-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= Yii::t('app', 'Меню'); ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/projects"><?= Yii::t('app', 'Проекты'); ?></a></li>
                    <li><a class="dropdown-item" href="/entries"><?= Yii::t('app', 'Новости'); ?></a></li>
                    <li><a class="dropdown-item" href="/project-agents"><?= Yii::t('app', 'Агенты'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php
        if (!empty($this->params['breadcrumbs'])) { ?>
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'],
                'homeLink' => ['label' => Yii::t('app', 'Главная'), 'url' => '/site/index'],
            ]); ?>
        <?php } ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-12">
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent text-muted fw-bolder fs-5">
                        <?= Yii::t('app', 'Меню'); ?>
                    </li>
                    <li class="list-group-item bg-transparent text-muted">
                        <a href="/projects"><?= Yii::t('app', 'Проекты'); ?></a>
                    </li>
                    <li class="list-group-item bg-transparent text-muted">
                        <a href="/entries"><?= Yii::t('app', 'Новости'); ?></a>
                    </li>
                    <li class="list-group-item bg-transparent text-muted">
                        <a href="/project-agents"><?= Yii::t('app', 'Агенты'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; My Company</div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::t('app', '2024 г.'); ?></div>
        </div>
    </div>
</footer>

<?php
$this->endBody() ?>
</body>
</html>
<?php
$this->endPage() ?>
