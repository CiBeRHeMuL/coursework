<?php

use app\enum\ProjectStatusEnum;
use app\helpers\HEnum;
use app\helpers\HHtml;
use app\models\Project;
use app\widgets\ActiveForm;
use app\widgets\Select2;
use yii\web\View;

/**
 * @var string $title
 * @var Project $project
 * @var View $this
 */

$form = ActiveForm::begin();
?>
<div class="row">
    <h4><?= $title; ?></h4>
    <div class="col-12">
        <?= $form->errorSummary([$project]) ?>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header fw-bolder">
                <?= Yii::t('app', 'Основная информация'); ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <?= $form->field($project, 'name')->textInput(); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($project, 'description')
                            ->textarea(['placeholder' => Yii::t('app', 'Подробное описание'), 'style' => 'min-height: 150px;']); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($project, 'agent_id')->select2Ajax(
                            '/project-agents/filter',
                            ['options' => ['placeholder' => Yii::t('app', 'Выбери агента')]],
                        ); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($project, 'status')->widget(
                            Select2::class,
                            [
                                'data' => HEnum::getCasesList(
                                    ProjectStatusEnum::class,
                                    $project->status?->getAvailableStatuses() ?? ProjectStatusEnum::getDefaultAvailableStatuses(),
                                    false,
                                ),
                                'hideSearch' => true,
                                'options' => ['placeholder' => Yii::t('app', 'Выбери статус')],
                            ],
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!$project->isNewRecord && $project->versions) { ?>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder">
                    <?= Yii::t('app', 'Работа с версиями'); ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($project, 'update_to_version_id')
                                ->select2Ajax(
                                    '/project-versions/filter?' . http_build_query(['project_id' => $project->id]),
                                    ['options' => ['placeholder' => Yii::t('app', 'Выберите версию')]],
                                ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-12">
        <?= HHtml::formButtonGroup(!$project->isNewRecord); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
