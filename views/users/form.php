<?php

use app\enum\FileUploadTypeEnum;
use app\enum\PreferredAgentCommunicationMethodEnum;
use app\helpers\HEnum;
use app\helpers\HHtml;
use app\models\User;
use app\widgets\ActiveForm;
use app\widgets\FileUploadWidget;
use app\widgets\Select2;
use borales\extensions\phoneInput\PhoneInput;
use yii\web\View;

/**
 * @var string $title
 * @var User $user
 * @var View $this
 */

$form = ActiveForm::begin();
?>
    <div class="row">
        <h4><?= $title; ?></h4>
        <div class="col-12">
            <?= $form->errorSummary([$user]) ?>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder">
                    <?= Yii::t('app', 'Личная информация'); ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($user, 'name')
                                ->textInput(['placeholder' => Yii::t('app', 'Имя')]); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($user, 'surname')
                                ->textInput(['placeholder' => Yii::t('app', 'Фамилия')]); ?>
                        </div>
                        <div class="col-12">
                            <div class="col-12">
                                <?= $form->field($user, 'avatar')->input('text')
                                    ->hint(Yii::t('app', 'Можно указать ссылку')); ?>
                            </div>
                            <?= $form->field($user, 'avatar')->widget(
                                FileUploadWidget::class,
                                ['type' => FileUploadTypeEnum::USER_AVATAR->value, 'extensions' => FileUploadTypeEnum::USER_AVATAR->getExtensions()]
                            )->hint(Yii::t('app', 'Можно загрузить вручную')); ?>
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
                            <?= $form->field($user, 'preferred_communication_method')->widget(
                                Select2::class,
                                [
                                    'data' => HEnum::getCasesList(PreferredAgentCommunicationMethodEnum::class),
                                ],
                            ); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($user, 'phone')->widget(PhoneInput::class); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($user, 'email')->input('email', ['placeholder' => 'example@email.com']); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($user, 'telegram')->telegramInput(['placeholder' => 'login']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <?= HHtml::formButtonGroup(!$user->isNewRecord); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>