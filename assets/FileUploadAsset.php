<?php

namespace app\assets;

use yii\web\AssetBundle;

class FileUploadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/file-upload.css',
    ];
    public $js = [
        'js/file-upload.js',
    ];
}
