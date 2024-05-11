<?php

namespace app\controllers;

use app\enum\EntryStatusEnum;
use app\filters\EntriesFilter;
use app\filters\ProjectsFilter;
use app\models\Project;
use app\responses\models\select2\Select2Pagination;
use app\responses\models\select2\Select2Response;
use app\responses\models\select2\Select2ResponseModel;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProjectsController extends AbstractController
{
    public function actionIndex(): string
    {
        $filter = new ProjectsFilter();
        return $this->render('index', compact('filter'));
    }

    public function actionFilter(string|null $query = null, int|null $id = null): Response
    {
        $filter = new ProjectsFilter(['name' => $query, 'id' => $id]);
        $provider = $filter->filterSearch();
        $models = $provider->getModels();
        $response = new Select2Response(
            array_map(fn($m) => new Select2ResponseModel(id: $m['id'], text: $m['name']), $models),
            Select2Pagination::getInstanceByDataProvider($provider->pagination),
        );
        return $this->asJson($response);
    }

    public function actionCreate(): Response|string
    {
        $data = $this->request->post();
        $project = new Project();
        if ($project->load($data)) {
            $this->performAjaxValidation($project);
            if ($project->save()) {
                $this->setSaveFlash();
                return $this->redirect(['/project-versions/create', 'project_id' => $project->id]);
            } else {
                $this->setValidationFlash();
            }
        }
        return $this->render('create', compact('project'));
    }

    public function actionUpdate(int $id): Response|string
    {
        $project = Project::findOne($id);
        if ($project !== null) {
            $data = $this->request->post();
            if ($project->load($data)) {
                $this->performAjaxValidation($project);
                if ($project->save()) {
                    $this->setSaveFlash();
                    if (!$project->versions) {
                        return $this->redirect(['/project-versions/create', 'project_id' => $project->id]);
                    }
                    return $this->redirect('/projects');
                } else {
                    $this->setValidationFlash();
                }
            }
            return $this->render('update', compact('project'));
        }
        throw new NotFoundHttpException();
    }

    public function actionView(int $id): string
    {
        $project = Project::findOne($id);
        if ($project !== null) {
            $entriesFilter = new EntriesFilter(['projectId' => $project->id, 'statuses' => [EntryStatusEnum::Published]]);
            $entriesProvider = $entriesFilter->search();
            $entries = $entriesProvider->query->limit(5)->all();
            return $this->render('view', compact('project', 'entries'));
        }
        throw new NotFoundHttpException();
    }
}
