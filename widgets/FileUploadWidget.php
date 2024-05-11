<?php

namespace app\widgets;

use app\assets\FileUploadAsset;
use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap5\Html;

class FileUploadWidget extends Widget
{
    public Model $model;
    public string $attribute;
    public string $type;

    public function run(): string
    {
        $this->registerJs(['id' => $this->getId(), 'url' => '/upload?' . http_build_query(['type' => $this->type])]);
        return $this->renderInput();
    }

    private function registerJs(array $config): void
    {
        $this->view->registerAssetBundle(FileUploadAsset::class);
        $config = json_encode($config);
        $this->view->registerJs(
            <<<JS
(function () {
    window.FileUpload($config, window.\$);
})();
JS,
        );
    }

    private function renderInput(): string
    {
        return Html::tag(
            'div',
            Html::tag(
                'div',
                Html::tag(
                    'div',
                    Html::tag('i', '', ['class' => 'fas fa-remove']),
                    ['class' => 'btn btn-secondary', 'style' => 'width: 35px;', 'id' => "{$this->getId()}-reset-button"],
                )
                . Html::tag(
                    'div',
                    Html::tag('div', Yii::t('app', 'Файл'), ['style' => 'pointer-events: none;'])
                    . Html::input(
                        'file',
                        '',
                        null,
                        [
                            'class' => 'opacity-0 position-absolute w-100',
                            'style' => 'left: 0; z-index: -1',
                            'accept' => '.zip,.tar',
                            'id' => "{$this->getId()}-file-input",
                        ],
                    )
                    . Html::activeInput(
                        'text',
                        $this->model,
                        $this->attribute,
                        ['class' => 'd-none', 'id' => "{$this->getId()}-success-result"],
                    ),
                    [
                        'class' => 'btn btn-primary position-relative',
                        'style' => 'width: 115px; cursor: pointer !important;',
                        'id' => "{$this->getId()}-open-file",
                    ],
                ),
                ['class' => 'btn-group', 'role' => 'group', 'style' => 'width: 150px;'],
            )
            . Html::tag(
                'div',
                '',
                ['class' => 'text-muted p-1', 'id' => "{$this->getId()}-file-name"],
            )
            . Html::tag(
                'div',
                '',
                ['class' => 'invalid-feedback w-100', 'id' => "{$this->getId()}-file-error"],
            ),
            ['class' => 'w-100 d-flex align-items-center flex-wrap', 'id' => "{$this->getId()}-file-upload"],
        );
    }
}
