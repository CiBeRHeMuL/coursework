<?php

use app\models\User;
use yii\web\View;

/**
 * @var User $user
 * @var View $this
 */

$title = Yii::t('app', 'Создание агента');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => '/users'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Создание'), 'url' => '/users/create'];
echo $this->render('form', compact('user', 'title'));
