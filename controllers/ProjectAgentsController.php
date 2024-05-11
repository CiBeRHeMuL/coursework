<?php

namespace app\controllers;

use app\filters\ProjectAgentsFilter;
use app\filters\ProjectsFilter;
use app\models\ProjectAgent;
use app\responses\models\select2\Select2Pagination;
use app\responses\models\select2\Select2Response;
use app\responses\models\select2\Select2ResponseModel;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProjectAgentsController extends AbstractController
{
    public function actionIndex(): string
    {
        $filter = new ProjectAgentsFilter();
        return $this->render('index', compact('filter'));
    }

    public function actionCreate(): Response|string
    {
        $data = $this->request->post();
        $projectAgent = new ProjectAgent();
        if ($projectAgent->load($data)) {
            $this->performAjaxValidation($projectAgent);
            if ($projectAgent->save()) {
                $this->setSaveFlash();
                return $this->redirect('/project-agents');
            } else {
                $this->setValidationFlash();
            }
        }
        return $this->render('create', compact('projectAgent'));
    }

    public function actionUpdate(int $id): Response|string
    {
        $projectAgent = ProjectAgent::findOne($id);
        if ($projectAgent !== null) {
            $data = $this->request->post();
            if ($projectAgent->load($data)) {
                $this->performAjaxValidation($projectAgent);
                if ($projectAgent->save()) {
                    $this->setSaveFlash();
                    return $this->redirect('/project-agents');
                } else {
                    $this->setValidationFlash();
                }
            }
            return $this->render('update', compact('projectAgent'));
        }
        throw new NotFoundHttpException();
    }

    public function actionFilter(string|null $query = null, int|null $id = null): Response
    {
        $filter = new ProjectAgentsFilter(['search' => $query, 'id' => $id]);
        $provider = $filter->filterSearch();
        /** @var ProjectAgent[] $models */
        $models = $provider->getModels();
        $response = new Select2Response(
            array_map(fn($m) => new Select2ResponseModel(id: $m->id, text: $m->contactName, html: $m->htmlContactName), $models),
            Select2Pagination::getInstanceByDataProvider($provider->pagination),
        );
        return $this->asJson($response);
    }

    public function actionView(int $id): string
    {
        $projectAgent = ProjectAgent::findOne($id);
        if ($projectAgent !== null) {
            $projectsFilter = new ProjectsFilter(['agentId' => $projectAgent->id]);
            $projectsDataProvider = $projectsFilter->search();
            return $this->render('view', compact('projectAgent', 'projectsDataProvider'));
        }
        throw new NotFoundHttpException();
    }
}
