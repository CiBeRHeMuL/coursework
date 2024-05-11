<?php

namespace app\widgets;

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

class ActiveField extends \yii\bootstrap5\ActiveField
{
    public $options = ['class' => 'form-group'];

    public function hiddenInput($options = []): static
    {
        Html::addCssClass($this->options, 'd-none');
        return parent::hiddenInput($options);
    }

    public function telegramInput(array $options = []): static
    {
        $options = array_merge($this->inputOptions, $options);
        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::tag(
            'div',
            Html::tag('span', '@', ['class' => 'input-group-text', 'id' => 'addon-wrapping'])
            . Html::activeInput('text', $this->model, $this->attribute, $options),
            ['class' => 'input-group flex-nowrap'],
        );

        return $this;
    }

    public function select2Ajax(string $url, array $options = []): static
    {
        $options = array_merge(
            [
                'data' => [],
                'pluginOptions' => [
                    'ajax' => [
                        'url' => $url,
                        'minimumResultsForSearch' => -1,
                        'cache' => true,
                        'delay' => 500,
                        'data' => new JsExpression(
                            <<<JS
function (params) {
    return {
        query: params.term,
        page: params.page || 1
    };
}
JS,
                        ),
                    ],
                    'templateResult' => new JsExpression('function(data) {return data.html || data.text;}'),
                    'templateSelection' => new JsExpression('function(data) {return data.text;}'),
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                ],
            ],
            $options,
        );

        return $this->widget(Select2::class, $options);
    }
}
