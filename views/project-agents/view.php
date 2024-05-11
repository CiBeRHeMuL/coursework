<?php

use app\helpers\HHtml;
use app\models\Project;
use app\models\ProjectAgent;
use app\widgets\DetailView;
use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\web\View;

/**
 * @var ProjectAgent $projectAgent
 * @var ActiveDataProvider $projectsDataProvider
 * @var View $this
 */

$this->title = $projectAgent->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Агенты'), 'url' => '/project-agents'];
$this->params['breadcrumbs'][] = ['label' => $projectAgent->fullName, 'url' => ['/project-agents/view', 'id' => $projectAgent->id]];
?>
<div class="row">
    <div class="col-12">
        <div class="fs-4"><?= Yii::t('app', 'Агент {a}', ['a' => $projectAgent->htmlContactName]); ?></div>
    </div>
    <div class="col-md-6 col-12">
        <?= DetailView::widget([
            'model' => $projectAgent,
            'attributes' => [
                'id',
                'name',
                'surname',
                [
                    'attribute' => 'preferred_communication_method',
                    'value' => fn(ProjectAgent $pa) => $pa->preferred_communication_method->getName() . " ({$pa->htmlPreferredContact})",
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'phone',
                    'value' => fn(ProjectAgent $pa) => $pa->htmlPhone,
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'email',
                    'value' => fn(ProjectAgent $pa) => $pa->htmlEmail,
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'telegram',
                    'value' => fn(ProjectAgent $pa) => $pa->htmlTelegram,
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'created_at',
                    'value' => fn(ProjectAgent $pa) => HHtml::dateUi($pa->created_at),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'updated_at',
                    'value' => fn(ProjectAgent $pa) => HHtml::dateUi($pa->updated_at),
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
