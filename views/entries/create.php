<?php

use app\models\Entry;
use yii\web\View;

/**
 * @var Entry $entry
 * @var View $this
 */

$title = Yii::t('app', 'Создание новости');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Новости'), 'url' => '/entries'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Создание'), 'url' => '/entries/create'];
echo $this->render('form', compact('entry', 'title'));
