<?php

use app\models\ProjectAgent;
use yii\web\View;

/**
 * @var ProjectAgent $projectAgent
 * @var View $this
 */

$title = Yii::t('app', 'Обновление агента');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Агенты'), 'url' => '/project-agents'];
$this->params['breadcrumbs'][] = ['label' => $projectAgent->fullName, 'url' => ['/project-agents/view', 'id' => $projectAgent->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Обновление'), 'url' => ['/project-agents/update', 'id' => $projectAgent->id]];
echo $this->render('form', compact('projectAgent', 'title'));
