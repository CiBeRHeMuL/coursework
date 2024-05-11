<?php

use app\enum\EntryStatusEnum;
use app\enum\EntryTypeEnum;
use app\helpers\HEnum;
use app\helpers\HHtml;
use app\models\Entry;
use app\widgets\ActiveForm;
use app\widgets\DateTimePicker;
use app\widgets\Select2;
use yii\web\JsExpression;
use yii\web\View;

/**
 * @var string $title
 * @var Entry $entry
 * @var View $this
 */

$form = ActiveForm::begin();
?>
    <div class="row">
        <h4><?= $title; ?></h4>
        <div class="col-12">
            <?= $form->errorSummary([$entry]) ?>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder">
                    <?= Yii::t('app', 'Основная информация'); ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if ($entry->isNewRecord) { ?>
                            <div class="col-12">
                                <?= $form->field($entry, 'project_id')->widget(
                                    Select2::class,
                                    [
                                        'data' => [],
                                        'pluginOptions' => [
                                            'ajax' => [
                                                'url' => '/projects/filter',
                                                'minimumResultsForSearch' => -1,
                                                'cache' => true,
                                                'delay' => 500,
                                                'data' => new JsExpression(
                                                    'function (params) {
                                        return {
                                            query: params.term,
                                            page: params.page || 1
                                        };
                                    }',
                                                ),
                                            ],
                                        ],
                                        'options' => ['placeholder' => Yii::t('app', 'Выбери проект')],
                                    ],
                                ); ?>
                            </div>
                        <?php } ?>
                        <div class="col-12">
                            <?= $form->field($entry, 'title')
                                ->textInput(['placeholder' => Yii::t('app', 'Заголовок')]); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($entry, 'text')
                                ->textarea(['placeholder' => Yii::t('app', 'Подробное описание'), 'style' => 'min-height: 150px;']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder">
                    <?= Yii::t('app', 'Внутренняя информация'); ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($entry, 'type')->widget(
                                Select2::class,
                                [
                                    'data' => HEnum::getCasesList(EntryTypeEnum::class, withEmptyChoice: false),
                                    'hideSearch' => true,
                                    'options' => ['placeholder' => Yii::t('app', 'Выбери тип')],
                                ],
                            ); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($entry, 'status')->widget(
                                Select2::class,
                                [
                                    'data' => HEnum::getCasesList(EntryStatusEnum::class, $entry->status?->getAvailableStatuses(), false),
                                    'hideSearch' => true,
                                    'options' => ['placeholder' => Yii::t('app', 'Выбери статус')],
                                ],
                            ); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($entry, 'published_at')->widget(
                                DateTimePicker::class,
                                [
                                    'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                    'options' => ['placeholder' => Yii::t('app', 'Дата публикации')],
                                ],
                            )->hint(
                                Yii::t(
                                    'app',
                                    'Заполните поле для статуса "{status}"',
                                    ['status' => EntryStatusEnum::Scheduled->getName()],
                                ),
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <?= HHtml::formButtonGroup(!$entry->isNewRecord); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>