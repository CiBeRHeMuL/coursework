<?php

use app\models\Project;
use yii\web\View;

/**
 * @var Project $project
 * @var View $this
 */

$title = Yii::t('app', 'Создание проекта');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Проекты'), 'url' => '/projects'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Создание'), 'url' => '/projects/create'];
echo $this->render('form', compact('project', 'title'));
