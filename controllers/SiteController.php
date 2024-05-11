<?php

namespace app\controllers;

use app\enum\EntryStatusEnum;
use app\enum\ProjectStatusEnum;
use app\filters\EntriesFilter;
use app\filters\ProjectsFilter;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex(): string
    {
        $entriesFilter = new EntriesFilter(['statuses' => [EntryStatusEnum::Published]]);
        $entriesQuery = $entriesFilter->search()->query->limit(5);
        $entries = $entriesQuery->all();
        $projectsFilter = new ProjectsFilter(['statuses' => [ProjectStatusEnum::Active]]);
        $projectsQuery = $projectsFilter->search()->query->limit(5);
        $projects = $projectsQuery->all();
        return $this->render('index', compact('entries', 'projects'));
    }

    public function actionError()
    {
    }
}
