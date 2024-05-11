<?php

use app\filters\ProjectAgentsFilter;
use app\helpers\HHtml;
use app\models\ProjectAgent;
use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\web\View;

/**
 * @var ProjectAgentsFilter $filter
 * @var View $this
 */

$this->title = Yii::t('app', 'Новости');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Агенты'), 'url' => '/project-agents'];
?>

<?= Html::tag('h3', Yii::t('app', 'Новости')); ?>
<div style="padding: 10px 0">
    <?= Html::a(
        Html::button(
            Html::tag('i', '', ['class' => 'fas fa-plus', 'style' => 'margin-right: 5px;'])
            . Html::tag('span', Yii::t('app', 'Создать')),
            ['class' => 'btn btn-success'],
        ),
        '/project-agents/create',
    ); ?>
</div>

<?= GridView::widget([
    'dataProvider' => $filter->search(),
    'columns' => [
        [
            'attribute' => 'id',
            'fixed' => true,
        ],
        [
            'class' => ActionColumn::class,
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => fn($url, ProjectAgent $pa) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-pencil']),
                    $url,
                    ['title' => Yii::t('app', 'Обновить')],
                ),
                'delete' => fn($url, ProjectAgent $pa) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-trash text-danger']),
                    $url,
                    ['title' => Yii::t('app', 'Удалить')],
                ),
            ],
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
        [
            'attribute' => 'name',
            'value' => fn(ProjectAgent $pa) => $pa->fullName,
        ],
        [
            'attribute' => 'preferred_communication_method',
            'value' => fn(ProjectAgent $pa) => $pa->preferred_communication_method->getName(),
        ],
        [
            'attribute' => 'phone',
        ],
        [
            'attribute' => 'email',
        ],
        [
            'attribute' => 'telegram',
        ],
    ],
]);
?>
