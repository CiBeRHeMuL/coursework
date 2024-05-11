<?php

use app\helpers\HHtml;
use app\models\ProjectVersion;
use app\widgets\ActiveForm;
use app\widgets\DateTimePicker;
use app\widgets\FileUploadWidget;
use yii\web\View;

/**
 * @var View $this
 * @var ProjectVersion $projectVersion
 * @var string $title
 */

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>
    <div class="row">
        <h4><?= $title; ?></h4>
        <div class="col-12">
            <?= $form->errorSummary([$projectVersion]); ?>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder">
                    <?= Yii::t('app', 'Основная информация'); ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if (!$projectVersion->project) { ?>
                            <div class="col-12">
                                <?= $form->field($projectVersion, 'project_id')->select2Ajax(
                                    '/projects/filter',
                                    ['options' => ['placeholder' => Yii::t('app', 'Выберите проект')]],
                                ); ?>
                            </div>
                        <?php } else { ?>
                            <div class="col-12">
                                <?= $form->field($projectVersion, 'project_id')->hiddenInput()->label(false); ?>
                                <div class="text-muted fw-bolder">
                                    <?php if ($projectVersion->project->currentVersion) { ?>
                                        <?= Yii::t(
                                            'app',
                                            'Последняя версия проекта — {v}',
                                            ['v' => $projectVersion->project->currentVersion->name],
                                        ); ?>
                                    <?php } else { ?>
                                        <?= Yii::t(
                                            'app',
                                            'Эта версия будет первой',
                                        ); ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-12">
                            <?= $form->field($projectVersion, 'name')->textInput(['placeholder' => Yii::t('app', 'Название')]); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($projectVersion, 'active_since')->widget(DateTimePicker::class); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($projectVersion, 'is_current')->checkbox()
                                ->label(Yii::t('app', 'Обновить проект до этой версии'))
                                ->hint(Yii::t('app', 'То есть эта версия станет текущей для проекта')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder">
                    <?= Yii::t('app', 'Наполнение'); ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($projectVersion, 'description')
                                ->textarea(['placeholder' => Yii::t('app', 'Подробное описание'), 'style' => 'min-height: 150px;']); ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12">
                            <?= Yii::t('app', 'Можно загрузить архив с файлами версии вручную или указать ссылку на него'); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($projectVersion, 'files_url')->input('url')
                                ->hint(Yii::t('app', 'Можно указать ссылку на архив с файлами версии')); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($projectVersion, 'files_url')->widget(FileUploadWidget::class, ['type' => 'pv-files'])
                                ->label(Yii::t('app', 'Архив'))
                                ->hint(Yii::t('app', 'Можно загрузить архив вручную')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <?= HHtml::formButtonGroup(!$projectVersion->isNewRecord); ?>
        </div>
    </div>
<?php ActiveForm::end();
