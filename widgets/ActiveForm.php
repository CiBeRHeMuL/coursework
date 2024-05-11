<?php

namespace app\widgets;

use Yii;
use yii\base\Model;
use yii\bootstrap5\Html;

class ActiveForm extends \yii\bootstrap5\ActiveForm
{
    public $fieldClass = ActiveField::class;
    public $enableAjaxValidation = false;

    public function errorSummary($models, $options = []): string
    {
        Html::addCssClass($options, $this->errorSummaryCssClass);
        $options['encode'] = $this->encodeErrorSummary;
        $options['header'] = Yii::t('app', 'Исправьте следующие ошибки');
        return Html::errorSummary($models, $options);
    }

    /**
     * @param Model $model
     * @param string $attribute
     * @param array $options
     *
     * @return ActiveField
     */
    public function field($model, $attribute, $options = []): ActiveField
    {
        return parent::field($model, $attribute, $options);
    }
}
