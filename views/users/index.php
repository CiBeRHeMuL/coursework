<?php

use app\filters\UsersFilter;
use app\helpers\HHtml;
use app\models\User;
use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\web\View;

/**
 * @var UsersFilter $filter
 * @var View $this
 */

$this->title = Yii::t('app', 'Пользователи');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => '/users'];
?>

<?= Html::tag('h3', Yii::t('app', 'Пользователи')); ?>
<div style="padding: 10px 0">
    <?= Html::a(
        Html::button(
            Html::tag('i', '', ['class' => 'fas fa-plus', 'style' => 'margin-right: 5px;'])
            . Html::tag('span', Yii::t('app', 'Создать')),
            ['class' => 'btn btn-success'],
        ),
        '/users/create',
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
            'template' => '{view} {update}',
            'buttons' => [
                'update' => fn($url, User $pa) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-pencil']),
                    $url,
                    ['title' => Yii::t('app', 'Обновить')],
                ),
                'view' => fn($url, User $pa) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-eye text-success']),
                    $url,
                    ['title' => Yii::t('app', 'Посмотреть')],
                ),
            ],
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
        [
            'attribute' => 'name',
            'value' => fn(User $pa) => $pa->fullName,
        ],
        [
            'attribute' => 'preferred_communication_method',
            'value' => fn(User $pa) => $pa->preferred_communication_method->getName(),
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
