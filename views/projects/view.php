<?php

use app\models\Entry;
use app\models\Project;
use app\widgets\EntryCardWidget;
use yii\web\View;

/**
 * @var View $this
 * @var Project $project
 * @var Entry[] $entries
 */

$this->title = $project->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Проекты'), 'url' => '/projects'];
$this->params['breadcrumbs'][] = ['label' => $project->name, 'url' => ['/projects/view', 'id' => $project->id]];
?>
<div class="row">
    <div class="col-lg-6 col-12">
        <div class="row">
            <div class="col-12">
                <div class="h2"><?= $project->name; ?></div>
                <div class="<?= $project->status->getTextColorClass()->getTextClass(); ?>" style="margin-top: -10px;">
                    <?= $project->status->getName(); ?>
                </div>
            </div>
            <div class="col-12">
                <div class="border p-3 rounded-2"><?= $project->description; ?></div>
            </div>
            <?php if ($project->currentVersion) { ?>
                <div class="col-12">
                    <h4><?= Yii::t('app', 'Активная версия'); ?></h4>
                    <div class="text-muted" style="margin-top: -10px;">
                        <?= $project->currentVersion->name; ?>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border p-3 rounded-2"><?= $project->currentVersion->description; ?></div>
                    <a href="/project-versions/download/<?= $project->currentVersion->id; ?>" class="btn btn-success mt-2">
                        <?=
                        Yii::t(
                            'app',
                            'Скачать файлы версии',
                        );
                        ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-lg-6 col-12 border rounded-2 p-3">
        <div class="h4"><?= Yii::t('app', 'Последние новости'); ?></div>
        <div class="row h-100">
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
    </div>
</div>

