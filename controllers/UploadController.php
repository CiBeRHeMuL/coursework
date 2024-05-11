<?php

namespace app\controllers;

use app\models\upload\FileUploadResult;
use app\models\upload\UploadedFile;
use app\responses\models\upload\FileUploadResponse;
use app\responses\models\upload\UploadResponse;
use app\services\UploadService;
use yii\web\Response;

class UploadController extends AbstractController
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->response->format = Response::FORMAT_JSON;
    }

    public function actionIndex(string $type): UploadResponse
    {
        $files = [];
        foreach ($_FILES ?? [] as $key => $file) {
            $files[] = new UploadedFile($key, ...$file);
        }
        if ($files) {
            $uploadService = new UploadService();
            $results = $uploadService->uploadFiles($files, $type);
            return new UploadResponse(
                true,
                array_map(
                    fn(FileUploadResult $r) => new FileUploadResponse(
                        $r->getKey(),
                        $r->isSuccess(),
                        $r->getMessage(),
                        $r->getUrl(),
                    ),
                    $results,
                ),
            );
        }
        return new UploadResponse(false, []);
    }
}
