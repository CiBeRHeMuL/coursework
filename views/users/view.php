<?php

use app\helpers\HHtml;
use app\models\Project;
use app\models\User;
use app\widgets\DetailView;
use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\web\View;

/**
 * @var User $user
 * @var ActiveDataProvider $projectsDataProvider
 * @var View $this
 */

$this->title = $user->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => '/users'];
$this->params['breadcrumbs'][] = ['label' => $user->fullName, 'url' => ['/users/view', 'id' => $user->id]];
?>
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <?php if ($user->avatar) { ?>
                <img src="<?= $user->avatar; ?>" alt="" class="avatar">
            <?php } else { ?>
                <?= HHtml::avatarFromName($user->name); ?>
            <?php } ?>
            <div class="fs-4"><?= Yii::t('app', 'Пользователь {a}', ['a' => $user->htmlContactName]); ?></div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <?= DetailView::widget([
            'model' => $user,
            'attributes' => [
                'id',
                'name',
                'surname',
                [
                    'attribute' => 'preferred_communication_method',
                    'value' => fn(User $pa) => $pa->preferred_communication_method->getName() . " ({$pa->htmlPreferredContact})",
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'phone',
                    'value' => fn(User $pa) => $pa->htmlPhone,
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'email',
                    'value' => fn(User $pa) => $pa->htmlEmail,
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'telegram',
                    'value' => fn(User $pa) => $pa->htmlTelegram,
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'created_at',
                    'value' => fn(User $pa) => HHtml::dateUi($pa->created_at),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'updated_at',
                    'value' => fn(User $pa) => HHtml::dateUi($pa->updated_at),
                    'format' => 'raw',
                ],
            ],
        ]); ?>
    </div>
    <div class="col-12 mt-4">
        <div class="h4"><?= Yii::t('app', 'Проекты'); ?></div>
    </div>
    <div class="col-12">
        <?= GridView::widget([
            'dataProvider' => $projectsDataProvider,
            'columns' => [
                [
                    'attribute' => 'id',
                    'label' => Yii::t('app', '#'),
                    'fixed' => true,
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{update} {add_version}',
                    'buttons' => [
                        'update' => fn($url, Project $p) => Html::a(
                            Html::tag('i', '', ['class' => 'fas fa-pencil']),
                            $url,
                            ['title' => Yii::t('app', 'Обновить')],
                        ),
                        'add_version' => fn($url, Project $p) => Html::a(
                            Html::tag('i', '', ['class' => 'fas fa-circle-plus text-success']),
                            Yii::getAlias('@site/project-versions/create?' . http_build_query(['project_id' => $p->id])),
                            ['title' => Yii::t('app', 'Добавить версию')],
                        ),
                    ],
                ],
                [
                    'attribute' => 'created_at',
                    'label' => Yii::t('app', 'Создан'),
                    'value' => fn(Project $p) => HHtml::dateUi($p->created_at),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'updated_at',
                    'label' => Yii::t('app', 'Обновлен'),
                    'value' => fn(Project $p) => HHtml::dateUi($p->updated_at),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'name',
                    'value' => fn(Project $p) => Html::a($p->name, ['/projects/view', 'id' => $p->id]),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'value' => fn(Project $p) => Html::tag(
                        'div',
                        $p->status->getName(),
                        ['class' => 'badge ' . $p->status->getTextColorClass()->getTextClass()],
                    ),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'currentVersion.name',
                    'label' => Yii::t('app', 'Версия'),
                ],
            ],
        ]); ?>
    </div>
</div>
