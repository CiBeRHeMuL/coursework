<?php

namespace app\data;

use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Inflector;

class Sort extends \yii\data\Sort
{
    public function link($attribute, $options = []): string
    {
        $iClass = 'fa-solid fa-circle-dot';
        if (($direction = $this->getAttributeOrder($attribute)) !== null) {
            $class = $direction === SORT_DESC ? 'desc' : 'asc';
            $iClass = $direction === SORT_DESC ? 'fa-solid fa-arrow-up-wide-short' : 'fa-solid fa-arrow-down-short-wide';
            if (isset($options['class'])) {
                $options['class'] .= ' ' . $class;
            } else {
                $options['class'] = $class;
            }
        }
        $iClass .= ' text-muted sorter-col__sort';

        $url = $this->createUrl($attribute);
        $options['data-sort'] = $this->createSortParam($attribute);

        if (isset($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        } else {
            if (isset($this->attributes[$attribute]['label'])) {
                $label = $this->attributes[$attribute]['label'];
            } elseif ($this->modelClass !== null) {
                $modelClass = $this->modelClass;
                /** @var Model $model */
                $model = $modelClass::instance();
                $label = $model->getAttributeLabel($attribute);
            } else {
                $label = Inflector::camel2words($attribute);
            }
        }

        return Html::tag(
            'div',
            Html::tag('span', $label, ['class' => 'sorter-col__label'])
            . Html::a('', $url, ['class' => $iClass]),
            $options,
        );
    }
}
