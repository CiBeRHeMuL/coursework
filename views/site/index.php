<?php

use app\models\Entry;
use app\models\Project;
use app\widgets\EntryCardWidget;
use app\widgets\ProjectCardWidget;
use yii\web\View;

/**
 * @var Entry[] $entries
 * @var Project[] $projects
 * @var View $this
 */

$this->title = Yii::t('app', 'Главная');

?>
<h2><?= Yii::t('app', 'Наши проекты'); ?></h2>
<div class="row my-3">
    <?php if ($projects) { ?>
        <?php foreach ($projects as $project) { ?>
            <div class="col-xxl-6 col-xl-12">
                <?= ProjectCardWidget::widget(['project' => $project]) ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="col-12 d-flex align-items-center align-content-center justify-content-center">
            <div class="text-muted fw-500 fs-3">
                <?= Yii::t('app', 'Нет последних проектов'); ?>
            </div>
        </div>
    <?php } ?>
</div>
<h2><?= Yii::t('app', 'Последние новости'); ?></h2>
<div class="row my-3">
    <?php if ($entries) { ?>
        <?php foreach ($entries as $entry) { ?>
            <div class="col-lg-6 col-12">
                <?= EntryCardWidget::widget(['entry' => $entry, 'withProjectBtn' => false, 'withUpdateBtn' => false]); ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="col-12 d-flex align-items-center align-content-center justify-content-center">
            <div class="text-muted fw-500 fs-3">
                <?= Yii::t('app', 'Нет последних новостей'); ?>
            </div>
        </div>
    <?php } ?>
</div>
