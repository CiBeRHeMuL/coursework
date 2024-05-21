<?php

use app\models\User;
use yii\web\View;

/**
 * @var User $user
 * @var View $this
 */

$title = Yii::t('app', 'Обновление пользователя');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => '/users'];
$this->params['breadcrumbs'][] = ['label' => $user->fullName, 'url' => ['/users/view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Обновление'), 'url' => ['/users/update', 'id' => $user->id]];
echo $this->render('form', compact('user', 'title'));
