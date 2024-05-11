<?php

use app\enum\PreferredAgentCommunicationMethodEnum;
use app\helpers\HEnum;
use app\helpers\HHtml;
use app\models\ProjectAgent;
use app\widgets\ActiveForm;
use app\widgets\Select2;
use borales\extensions\phoneInput\PhoneInput;
use yii\web\View;

/**
 * @var string $title
 * @var ProjectAgent $projectAgent
 * @var View $this
 */

$form = ActiveForm::begin();
?>
    <div class="row">
        <h4><?= $title; ?></h4>
        <div class="col-12">
            <?= $form->errorSummary([$projectAgent]) ?>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder">
                    <?= Yii::t('app', 'Личная информация'); ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($projectAgent, 'name')
                                ->textInput(['placeholder' => Yii::t('app', 'Имя')]); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($projectAgent, 'surname')
                                ->textInput(['placeholder' => Yii::t('app', 'Фамилия')]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder">
                    <?= Yii::t('app', 'Контактная информация'); ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($projectAgent, 'preferred_communication_method')->widget(
                                Select2::class,
                                [
                                    'data' => HEnum::getCasesList(PreferredAgentCommunicationMethodEnum::class),
                                ],
                            ); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($projectAgent, 'phone')->widget(PhoneInput::class); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($projectAgent, 'email')->input('email', ['placeholder' => 'example@email.com']); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($projectAgent, 'telegram')->telegramInput(['placeholder' => 'login']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <?= HHtml::formButtonGroup(!$projectAgent->isNewRecord); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>