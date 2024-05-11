<?php

use app\models\ProjectVersion;
use yii\web\View;

/**
 * @var ProjectVersion $projectVersion
 * @var View $this
 */

$title = Yii::t('app', 'Добавление версии проекта');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Проекты'), 'url' => '/projects'];
$this->params['breadcrumbs'][] = ['label' => $projectVersion->project->name, 'url' => ['/projects/view', 'id' => $projectVersion->project_id]];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Создание версии'),
    'url' => ['/projects-versions/create', 'id' => $projectVersion->project_id],
];
echo $this->render('form', compact('projectVersion', 'title'));
