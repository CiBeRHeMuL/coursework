<?php

use app\models\ProjectAgent;
use yii\web\View;

/**
 * @var ProjectAgent $projectAgent
 * @var View $this
 */

$title = Yii::t('app', 'Создание агента');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Агенты'), 'url' => '/project-agents'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Создание'), 'url' => '/project-agents/create'];
echo $this->render('form', compact('projectAgent', 'title'));
