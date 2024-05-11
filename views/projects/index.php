<?php

use app\filters\ProjectsFilter;
use app\helpers\HHtml;
use app\models\Project;
use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\web\View;

/**
 * @var ProjectsFilter $filter
 * @var View $this
 */

$this->title = Yii::t('app', 'Проекты');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Проекты'), 'url' => '/projects'];
?>

<?= Html::tag('h3', Yii::t('app', 'Проекты')); ?>
<div style="padding: 10px 0">
    <?= Html::a(
        Html::button(
            Html::tag('i', '', ['class' => 'fas fa-plus', 'style' => 'margin-right: 5px;'])
            . Html::tag('span', Yii::t('app', 'Создать')),
            ['class' => 'btn btn-success'],
        ),
        '/projects/create',
    ); ?>
</div>

<?= GridView::widget([
    'dataProvider' => $filter->search(),
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
            'attribute' => 'agent_id',
            'value' => fn(Project $p) => Html::a("#$p->agent_id " . $p->agent->fullName, ['/project-agents/view', 'id' => $p->agent_id]),
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
]);
?>
