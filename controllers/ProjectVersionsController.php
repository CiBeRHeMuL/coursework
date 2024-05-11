<?php

namespace app\controllers;

use app\enum\EntryStatusEnum;
use app\enum\EntryTypeEnum;
use app\enum\ProjectStatusEnum;
use app\filters\ProjectVersionsFilter;
use app\models\Entry;
use app\models\ProjectVersion;
use app\responses\models\select2\Select2Pagination;
use app\responses\models\select2\Select2Response;
use app\responses\models\select2\Select2ResponseModel;
use DateTimeImmutable;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProjectVersionsController extends AbstractController
{
    public function actionCreate(int|null $project_id = null): Response|string
    {
        $data = $this->request->post();
        $projectVersion = new ProjectVersion(['project_id' => $project_id, 'is_current' => true]);
        if ($projectVersion->load($data)) {
            $this->performAjaxValidation($projectVersion);
            if ($projectVersion->save()) {
                $entry = new Entry([
                    'project_id' => $projectVersion->project_id,
                    'title' => Yii::t('app', '{p} обновился!', ['p' => $projectVersion->project->name]),
                    'status' => $projectVersion->project->status === ProjectStatusEnum::Active
                        ? EntryStatusEnum::Published
                        : EntryStatusEnum::Draft,
                    'text' => $projectVersion->description,
                    'type' => count($projectVersion->project->versions) > 1 ? EntryTypeEnum::ProjectUpdate : EntryTypeEnum::ProjectLaunch,
                    'published_at' => $projectVersion->project->status === ProjectStatusEnum::Active
                        ? new DateTimeImmutable()
                        : null,
                ]);
                if (!$entry->save()) {
                    $this->addFlash('warning', Yii::t('app', 'Не удалось создать новость, создайте ее вручную.'));
                }
                $this->setSaveFlash();
                return $this->redirect('/projects');
            } else {
                $this->setValidationFlash();
            }
        }
        return $this->render('create', compact('projectVersion'));
    }

    public function actionFilter(string|null $query = null, int|null $project_id = null, int|null $id = null): Response
    {
        $filter = new ProjectVersionsFilter(['projectId' => $project_id, 'id' => $id, 'name' => $query]);
        $provider = $filter->filterSearch();
        /** @var ProjectVersion[] $models */
        $models = $provider->getModels();
        $response = new Select2Response(
            array_map(
                fn(ProjectVersion $m) => new Select2ResponseModel(
                    id: $m->id,
                    text: $project_id === null ? "$m->name ({$m->project->name})" : $m->name,
                    html: $project_id === null ? "$m->name <a href='/projects/$m->project_id'>{$m->project->name}</a>" : $m->name,
                ),
                $models,
            ),
            Select2Pagination::getInstanceByDataProvider($provider->pagination),
        );
        return $this->asJson($response);
    }

    public function actionDownload(int $id): Response
    {
        $pv = ProjectVersion::findOne($id);
        if ($pv !== null) {
            if (str_starts_with($pv->files_url, Yii::getAlias('@site/'))) {
                $file = str_replace(Yii::getAlias('@site/'), '', $pv->files_url);
                return $this->response->sendFile($file);
            }
            return $this->redirect($pv->files_url);
        }
        throw new NotFoundHttpException();
    }
}
