<?php

namespace app\controllers;

use app\widgets\ActiveForm;
use Yii;
use yii\base\ExitException;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;

abstract class AbstractController extends Controller
{
    /**
     * @param Model $model
     *
     * @return void
     * @throws ExitException
     */
    public function performAjaxValidation(Model $model): void
    {
        if ($this->request->isAjax) {
            $this->response->format = Response::FORMAT_JSON;
            $validationErrors = ActiveForm::validate($model);
            if ($validationErrors) {
                $this->response->data = $validationErrors;
                $this->response->send();
                Yii::$app->end();
            }
        }
    }

    public function setFlash(string $type, string $message): void
    {
        Yii::$app->getSession()->setFlash($type, $message);
    }

    public function addFlash(string $type, string $message): void
    {
        Yii::$app->getSession()->addFlash($type, $message);
    }

    public function setSaveFlash(): void
    {
        Yii::$app->getSession()->removeFlash('danger');
        $this->setFlash('success', Yii::t('app', 'Данные сохранены!'));
    }

    public function setValidationFlash(): void
    {
        $this->setFlash('danger', Yii::t('app', 'Введенные данные содержат ошибки!'));
    }

    protected function setDeletedFlash(): void
    {
        $this->setFlash('info', Yii::t('app', 'Данные успешно удалены!'));
    }
}
