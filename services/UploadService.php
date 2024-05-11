<?php

namespace app\services;

use app\models\upload\FileUploadResult;
use app\models\upload\UploadedFile;
use Throwable;
use Yii;

class UploadService
{
    /**
     * @param UploadedFile[] $files
     *
     * @return FileUploadResult[]
     */
    public function uploadFiles(array $files, string $type): array
    {
        $results = [];
        foreach ($files as $file) {
            $filename = md5(microtime()) . ".{$file->getExtension()}";
            $saveDir = Yii::getAlias("@webroot/files/$type");
            if (!@mkdir($saveDir, 0755, true) && !is_dir($saveDir)) {
                $results[] = new FileUploadResult(
                    $file->getKey(),
                    false,
                    message: Yii::t('app', 'Ошибка загрузки'),
                );
            }
            $savePath = $saveDir . DIRECTORY_SEPARATOR . $filename;
            try {
                if (move_uploaded_file($file->getTmpName(), $savePath) && file_exists($savePath)) {
                    $results[] = new FileUploadResult(
                        $file->getKey(),
                        true,
                        url: Yii::getAlias("@site/files/$type/$filename"),
                    );
                } else {
                    $results[] = new FileUploadResult(
                        $file->getKey(),
                        false,
                        message: Yii::t('app', 'Не удалось сохранить файл'),
                    );
                }
            } catch (Throwable $e) {
                $results[] = new FileUploadResult(
                    $file->getKey(),
                    false,
                    message: Yii::t('app', 'Не удалось сохранить файл'),
                );
            }
        }
        return $results;
    }
}
