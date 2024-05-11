<?php

use app\models\Project;
use yii\web\View;

/**
 * @var Project $project
 * @var View $this
 */

$title = Yii::t('app', 'Обновление проекта');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Проекты'), 'url' => '/projects'];
$this->params['breadcrumbs'][] = ['label' => $project->name, 'url' => ['/projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Обновление'), 'url' => ['/projects/update', 'id' => $project->id]];
echo $this->render('form', compact('project', 'title'));
