<?php

namespace app\controllers;

use app\filters\ProjectsFilter;
use app\filters\UsersFilter;
use app\helpers\HHtml;
use app\models\User;
use app\responses\models\select2\Select2Pagination;
use app\responses\models\select2\Select2Response;
use app\responses\models\select2\Select2ResponseModel;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UsersController extends AbstractController
{
    public function actionIndex(): string
    {
        $filter = new UsersFilter();
        return $this->render('index', compact('filter'));
    }

    public function actionCreate(): Response|string
    {
        $data = $this->request->post();
        $user = new User();
        if ($user->load($data)) {
            $this->performAjaxValidation($user);
            if ($user->save()) {
                $this->setSaveFlash();
                return $this->redirect('/users');
            } else {
                $this->setValidationFlash();
            }
        }
        return $this->render('create', compact('user'));
    }

    public function actionUpdate(int $id): Response|string
    {
        $user = User::findOne($id);
        if ($user !== null) {
            $data = $this->request->post();
            if ($user->load($data)) {
                $this->performAjaxValidation($user);
                if ($user->save()) {
                    $this->setSaveFlash();
                    return $this->redirect('/users');
                } else {
                    $this->setValidationFlash();
                }
            }
            return $this->render('update', compact('user'));
        }
        throw new NotFoundHttpException();
    }

    public function actionFilter(string|null $query = null, int|null $id = null): Response
    {
        $filter = new UsersFilter(['search' => $query, 'id' => $id]);
        $provider = $filter->filterSearch();
        /** @var User[] $models */
        $models = $provider->getModels();
        $response = new Select2Response(
            array_map(
                fn($m) => new Select2ResponseModel(
                    id: $m->id,
                    text: $m->contactName,
                    html: ($m->avatar ? HHtml::avatar($m->avatar, true) : HHtml::avatarFromName($m->name, true)) . $m->htmlContactName
                ),
                $models,
            ),
            Select2Pagination::getInstanceByDataProvider($provider->pagination),
        );
        return $this->asJson($response);
    }

    public function actionView(int $id): string
    {
        $user = User::findOne($id);
        if ($user !== null) {
            $projectsFilter = new ProjectsFilter(['agentId' => $user->id]);
            $projectsDataProvider = $projectsFilter->search();
            return $this->render('view', compact('user', 'projectsDataProvider'));
        }
        throw new NotFoundHttpException();
    }
}
