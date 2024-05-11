<?php

use app\models\Entry;
use yii\web\View;

/**
 * @var Entry $entry
 * @var View $this
 */

$title = Yii::t('app', 'Обновление новости');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Новости'), 'url' => '/entries'];
$this->params['breadcrumbs'][] = ['label' => $entry->title, 'url' => ['/entries/view', 'id' => $entry->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Обновление'), 'url' => ['/entries/update', 'id' => $entry->id]];
echo $this->render('form', compact('entry', 'title'));
