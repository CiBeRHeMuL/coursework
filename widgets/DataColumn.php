<?php

namespace app\widgets;

use yii\bootstrap5\Html;

class DataColumn extends \yii\grid\DataColumn
{
    public $encodeLabel = false;
    public string|null $minWidth = null;
    public string|null $maxWidth = null;
    public bool $fixed = false;
    public $headerOptions = ['class' => 'sorter-col'];
    public $filterInputOptions = ['class' => 'form-control form-control-sm', 'id' => null];

    public function init(): void
    {
        parent::init();

        if ($this->attribute == 'id') {
            Html::addCssClass($this->headerOptions, 'id');
            Html::addCssClass($this->contentOptions, 'id');
        }

        if ($this->minWidth) {
            $this->contentOptions['style']['min-width'] = $this->minWidth;
        }
        if ($this->maxWidth) {
            $this->contentOptions['style']['max-width'] = $this->maxWidth;
        }

        if ($this->fixed) {
            Html::addCssClass($this->contentOptions, 'column-fixed');
            Html::addCssClass($this->headerOptions, 'column-fixed');
            Html::addCssClass($this->filterOptions, 'column-fixed');
            Html::addCssClass($this->footerOptions, 'column-fixed');
        }
    }
}
