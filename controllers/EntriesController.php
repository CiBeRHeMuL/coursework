<?php

namespace app\controllers;

use app\filters\EntriesFilter;
use app\models\Entry;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class EntriesController extends AbstractController
{
    public function actionIndex(): string
    {
        $filter = new EntriesFilter();
        return $this->render('index', compact('filter'));
    }

    public function actionCreate(): Response|string
    {
        $data = $this->request->post();
        $entry = new Entry();
        if ($entry->load($data)) {
            $this->performAjaxValidation($entry);
            if ($entry->save()) {
                $this->setSaveFlash();
                return $this->redirect('/entries');
            } else {
                $this->setValidationFlash();
            }
        }
        return $this->render('create', compact('entry'));
    }

    public function actionUpdate(int $id): Response|string
    {
        $entry = Entry::findOne($id);
        if ($entry !== null) {
            $data = $this->request->post();
            if ($entry->load($data)) {
                $this->performAjaxValidation($entry);
                if ($entry->save()) {
                    $this->setSaveFlash();
                    return $this->redirect('/entries');
                } else {
                    $this->setValidationFlash();
                }
            }
            return $this->render('update', compact('entry'));
        }
        throw new NotFoundHttpException();
    }

    public function actionDelete(int $id): Response
    {
        Entry::deleteAll(['id' => $id]);
        $this->setDeletedFlash();
        return $this->redirect('/entries');
    }

    public function actionView(int $id): string
    {
        $entry = Entry::findOne($id);
        if ($entry !== null) {
            return $this->render('view', compact('entry'));
        }
        throw new NotFoundHttpException();
    }
}
