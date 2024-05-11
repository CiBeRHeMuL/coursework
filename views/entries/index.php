<?php

use app\filters\EntriesFilter;
use app\helpers\HHtml;
use app\helpers\HStrings;
use app\models\Entry;
use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\web\View;

/**
 * @var EntriesFilter $filter
 * @var View $this
 */

$this->title = Yii::t('app', 'Новости');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Новости'), 'url' => '/entries'];
?>

<?= Html::tag('h3', Yii::t('app', 'Новости')); ?>
<div style="padding: 10px 0">
    <?= Html::a(
        Html::button(
            Html::tag('i', '', ['class' => 'fas fa-plus', 'style' => 'margin-right: 5px;'])
            . Html::tag('span', Yii::t('app', 'Создать')),
            ['class' => 'btn btn-success'],
        ),
        '/entries/create',
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
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => fn($url, Entry $e) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-eye text-success']),
                    $url,
                    ['title' => Yii::t('app', 'Просмотреть')],
                ),
                'update' => fn($url, Entry $e) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-pencil']),
                    $url,
                    ['title' => Yii::t('app', 'Обновить')],
                ),
                'delete' => fn($url, Entry $e) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-trash text-danger']),
                    $url,
                    ['title' => Yii::t('app', 'Удалить')],
                ),
            ],
        ],
        [
            'attribute' => 'created_at',
            'value' => fn(Entry $e) => HHtml::dateUi($e->created_at),
            'format' => 'raw',
        ],
        [
            'attribute' => 'updated_at',
            'value' => fn(Entry $e) => HHtml::dateUi($e->updated_at),
            'format' => 'raw',
        ],
        [
            'attribute' => 'title',
            'value' => fn(Entry $e) => HStrings::crop($e->title, 25),
        ],
        [
            'attribute' => 'status',
            'value' => fn(Entry $e) => Html::tag(
                'div',
                $e->status->getName(),
                ['class' => 'badge ' . $e->status->getTextColorClass()->getTextClass()],
            ),
            'format' => 'raw',
        ],
        [
            'attribute' => 'type',
            'value' => fn(Entry $e) => Html::tag(
                'div',
                $e->type->getName(),
                ['class' => 'badge ' . $e->type->getTextColorClass()->getTextClass()],
            ),
            'format' => 'raw',
        ],
        [
            'attribute' => 'project_id',
            'value' => fn(Entry $e) => Html::a($e->project->name, "/projects/$e->project_id", ['target' => '_blank']),
            'format' => 'raw',
        ],
    ],
]);
?>
