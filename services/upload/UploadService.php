<?php

namespace app\services\upload;

use app\enum\FileUploadTypeEnum;
use app\services\upload\models\FileUploadResult;
use app\services\upload\models\UploadedFile;
use Throwable;
use Yii;

class
UploadService
{
    /**
     * @param UploadedFile[] $files
     *
     * @return FileUploadResult[]
     */
    public function uploadFiles(array $files, string $type): array
    {
        $configType = FileUploadTypeEnum::tryFrom($type);
        $results = [];
        foreach ($files as $file) {
            if ($configType === null) {
                $results[] = new FileUploadResult(
                    $file->getKey(),
                    false,
                    message: Yii::t('app', 'Неизвестный тип'),
                );
                continue;
            }
            $config = $configType->getConfig();
            if ($config->getChecker()->check($file)) {
                $filename = md5(microtime()) . ".{$file->getExtension()}";
                $saveDir = Yii::getAlias("@webroot/files/{$config->getDir()}");
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
                            url: Yii::getAlias("@site/files/{$config->getDir()}/$filename"),
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
            } else {
                $results[] = new FileUploadResult(
                    $file->getKey(),
                    false,
                    message: $config->getChecker()->getLastError() ?? Yii::t('app', 'Неизвестная ошибка'),
                );
            }
        }
        return $results;
    }
}
